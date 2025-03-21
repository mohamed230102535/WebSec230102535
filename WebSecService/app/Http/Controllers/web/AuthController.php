<?php
namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Artisan;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Crypt;
use App\Mail\VerificationEmail;


class AuthController extends Controller{
    use ValidatesRequests;

    //     public function __construct()
    // {
    //     $this->middleware('auth:web')->except([
    //         'list', 
    //         'login', 
    //         'register', 
    //         'doLogin', 
    //         'doRegister', 
    //         'forgotPassword', 
    //         'doResetPassword',
    //         'index',
    //         'dashboard',
    //         'userAccount'
    //     ]);
    // }


    public function index(Request $request){

        return view('WebAuthentication.index');
    }
        
    public function login(Request $request){
        return view('WebAuthentication.login');
    }

    public function register(Request $request){
        return view('WebAuthentication.register');
    }

    public function doLogout(Request $request) {
        Auth::logout();
        return redirect()->route('WebAuthentication.index'); 
    }

    public function doLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())->withErrors(['Invalid login information.']);
        }
    
        // if (!$user->email_verified_at) {
        //     Auth::logout();
        //     return redirect()->route('WebAuthentication.login')->withErrors(['Your email is not verified. Please check your email.']);
        // }
    
        return redirect()->route('WebAuthentication.index');
    }
    
    
    public function doRegister(Request $request){
        // Validate the request
        $this->validate($request, [
            'name' => 'required|string|min:5',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);
    
      
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'This email is already registered. Please login or reset your password.']);
        }
        
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    
       
        // $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        // $link = route("verify", ['token' => $token]);
        // Mail::to($user->email)->send(new VerificationEmail($link, $user->name));
    
        return redirect()->route('WebAuthentication.login')
            ->with('success', 'Registration successful! Please check your email to verify your account.');

    }

    // public function verify(Request $request) {
    //     try {
    //         $decryptedData = json_decode(Crypt::decryptString($request->token), true);
    //         $user = User::find($decryptedData['id']);
    
    //         if (!$user) {
    //             abort(401, 'Invalid verification link.');
    //         }
    
    //         if ($user->email_verified_at) {
    //             return redirect()->route('WebAuthentication.login')->with('info', 'Your email is already verified.');
    //         }
    
    //         // Mark user as verified
    //         $user->email_verified_at = Carbon::now();
    //         $user->save();
    
    //         return redirect()->route('WebAuthentication.login')->with('success', 'Email verified successfully! You can now log in.');
    //     } catch (\Exception $e) {
    //         return redirect()->route('WebAuthentication.login')->withErrors(['error' => 'Invalid or expired verification link.']);
    //     }
    // }
       


//============================================================================================================
    public function userAccount(){
        $user = $user??auth()->user();
        $permissions = [];
      
        foreach($user->roles as $role) {
        foreach($role->permissions as $permission) {
        $permissions[] = $permission;
        }
        }

        return view('WebAuthentication.userAccount', compact('user', 'permissions'));

       
    }

    public function updateUsername(Request $request){
        $user = Auth::user();
        $user->name = $request->new_username;
        $user->save();
        return redirect()->route('WebAuthentication.userAccount')->with('success', 'Username updated successfully.');
    }
    public function updatePassword(Request $request){
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);

        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['Current password is incorrect.']);
        }

        if ($request->new_password != $request->confirm_password) {
            return redirect()->back()->withErrors(['New passwords do not match.']);
        }
    
        $user->password = bcrypt($request->new_password);
        $user->save();
        
        Auth::logout();
        
        return redirect()->route('WebAuthentication.login')
            ->with('success', 'Password updated successfully. Please login with your new password.');
    }

    public function forgotPassword(Request $request){
        return view('WebAuthentication.forgetPassword');
    }
    
   
    public function doResetPassword(Request $request){
        try {
           
            $this->validate($request, [
                'email' => 'required|email'
            ]);

           
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No account found with this email address.');
            }

            $this->validate($request, [
                'new_password' => 'required|min:6|confirmed',
            ]);

            if (Auth::check() && Auth::user()->email !== $request->email) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'You can only reset the password for your own account.');
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return redirect()->route('WebAuthentication.login')
                ->with('success', 'Password has been reset successfully. Please login with your new password.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);
        }
    }
//============================================================================================================
public function dashboard(Request $request)
{
    if (!auth()->user()->hasPermissionTo('dashboard')) {
        abort(404);
        }
        
        $query = User::query();

        if ($keywords = $request->input('keywords')) {
            $query->where('name', 'like', "%{$keywords}%")
                  ->orWhere('email', 'like', "%{$keywords}%");
        }

        $users = $query->get();
       
   
    return view('WebAuthentication.dashboard', compact('users'));
}


// Edit User
public function editUser($id) 
{
    $user = User::findOrFail($id);

    if (auth()->id() != $user->id) {
        abort_if(!auth()->user()->hasPermissionTo('editUser'), 404);
    }

    $roles = Role::all()->map(function ($role) use ($user) {
        $role->taken = $user->hasRole($role->name);
        return $role;
    });

    $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
    $permissions = Permission::all()->map(function ($permission) use ($directPermissionsIds) {
        $permission->taken = in_array($permission->id, $directPermissionsIds);
        return $permission;
    });

    return view('WebAuthentication.users.edit', compact('user', 'roles', 'permissions'));
}

// Create User
public function createUser(Request $request, User $user = null)
{
    if(auth()->id()!=$user?->id) {
    if(!auth()->user()->hasPermissionTo('createUser')) abort(404);
    }
    return view('WebAuthentication.users.create');
}

// Store User
public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return redirect()->route('WebAuthentication.dashboard')
        ->with('success', 'User created successfully');
}


// Update User 
public function updateUser(Request $request, $id) // $id is user ID to update
{
   
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email,' , 
     
    ]);

    $userData = [
        'name' => $request->name,
        'email' => $request->email,
    ];

    if ($request->filled('password')) {
        $userData['password'] = bcrypt($request->password);
    }
    $user->syncRoles($request->roles);
    $user->syncPermissions($request->permissions);
   
    $user->update($userData);
    Artisan::call('cache:clear');
    return redirect()->route('WebAuthentication.dashboard')
        ->with('success', 'User updated successfully');
}


public function deleteUser($id)
{
    if (!auth()->user()->hasPermissionTo('deleteUser')) {
        abort(401);
        }

    $user = User::findOrFail($id); 
    $user->delete();

    return redirect()->route('WebAuthentication.dashboard')
        ->with('success', 'User deleted successfully');
}
}