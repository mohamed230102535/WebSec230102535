<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\ForgetPassEmail;


class UsersController extends Controller {

   
	use ValidatesRequests;

    public function list(Request $request) {
        // Permission check removed temporarily
        // Previously checked for show_users permission
    
        $query = User::select('*');
    
        // Role check removed temporarily
        // Previously filtered customers for employees
    
        // Apply search filter if keywords are provided
        $query->when($request->keywords, 
            fn($q) => $q->where("name", "like", "%$request->keywords%")
        );
    
        // Get the users based on the query
        $users = $query->get();
    
        // Return the view with the filtered users
        return view('users.list', compact('users'));
    }
    

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {

        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        }

        catch(\Exception $e) {

            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }

       

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); 
        $user->save();

        // Role and permission assignments temporarily removed
        // Previously assigned Customer role and shopping cart permissions

        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect('/');
    }

    public function verify(Request $request) {

        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if(!$user) abort(401);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('users.verified', compact('user'));
       }

    // First profile method removed to fix 'Cannot redeclare' error
    // Consolidated with the more comprehensive profile method below
    
    /**
     * Update the user's profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        
        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Show the change password form
     */
    public function changePassword()
    {
        return view('users.change-password');
    }
    
    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('profile')->with('success', 'Password changed successfully!');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return redirect()->back()
            ->withInput($request->input())
            ->withErrors('No account found with this email.');
    }

    if (!$user->email_verified_at) {
        return redirect()->back()
            ->withInput($request->input())
            ->withErrors('Your email is not verified.');
    }

    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        return redirect()->back()
            ->withInput($request->input())
            ->withErrors('Invalid login information.');
    }

    Auth::setUser($user);
    return redirect('/');
}

    public function doLogout(Request $request) {
    	
    	Auth::logout();

        return redirect('/');
    }

   
       

    public function profile(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
    
        if (auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('show_users')) {
                abort(401);
            }
        }
    
        $purchasedProducts = $user->purchases()->with('product')->get();
    
        $permissions = [];
        foreach ($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
    
        return view('users.profile', compact('user', 'permissions', 'purchasedProducts'));
    }
    

    public function edit(Request $request, User $user = null) {
   
        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }
    
        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }      

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {

        if(auth()->id() != $user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }
        $user->name = $request->name;
        
        if ($request->has('charge_credit') && $request->charge_credit > 0) {
            $chargeAmount = $request->charge_credit;
    
            if ($chargeAmount <= 0) {
                return redirect()->back()->withErrors('The credit charge must be a positive value.');
            }
            $user->credit += $chargeAmount;
            $user->save();
    
        }
    

        // Permission check removed temporarily
        // Previously checked for admin_users permission
        if($request->has('roles')) {
            $user->syncRoles($request->roles);
        }
        if($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }
        
        Artisan::call('cache:clear');
    
        return redirect(route('profile', ['user' => $user->id]));
    }
    

    public function delete(Request $request, User $user) {
        // Permission check removed temporarily
        // Previously checked for delete_users permission

        //$user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        // Permission check removed temporarily
        // Previously checked for edit_users permission

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }


public function create(Request $request) {
    // Permission check removed temporarily
    // Previously checked for admin_users permission

    $roles = Role::all();
    $permissions = Permission::all();

    return view('users.add', compact('roles', 'permissions'));
}

public function store(Request $request) {
    // Permission check removed temporarily
    // Previously checked for admin_users permission

    $request->validate([
        'name' => ['required', 'string', 'min:3'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();

    if ($request->has('roles')) {
        $user->syncRoles($request->roles);
        
        // Customer permission assignments removed temporarily
    }

    if ($request->has('permissions')) {
        $user->syncPermissions($request->permissions);
    }

    Artisan::call('cache:clear');

    return redirect()->route('profile', ['user' => $user->id]);
}


//==========================================================================================================================

public function showForgotForm()
{
    return view('auth.forgot-password');
}

public function sendResetLink(Request $request)
{
    $this->validate($request, [
        'email' => ['required', 'email'],
    ]);
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return back()->withErrors(['email' => 'This email is not registered in our system.']);
    }
    if ($user->email_verified_at == null) {
        return back()->withErrors(['email' => 'Your email is not verified.']);
    }
    $title = "Reset Password Link";
    $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
    $link = route("ShowRestForm", ['token' => $token]);


    Mail::to($user->email)->send(new  ForgetPassEmail($link, $user->name));

    return redirect()->route('login')->with('status', 'Password reset link sent to your email.');
}



public function showResetLink(Request $request)
{
    $decryptedData = json_decode(Crypt::decryptString($request->token), true);
    $user = User::find($decryptedData['id']);
    if(!$user) abort(401);

    return view('auth.reset-password', compact('user'));
}


public function resetPassword(Request $request)
{
    $request->validate([
        'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
        'token' => ['required'], 
    ]);

    // Decrypt token and get user
    $decryptedData = json_decode(Crypt::decryptString($request->token), true);
    $user = User::find($decryptedData['id']);

    if (!$user) {
        return redirect()->route('login')->withErrors(['email' => 'Invalid or expired token.']);
    }

    // Reset the password
    $user->password = bcrypt($request->password); 
    $user->save();

    return redirect()->route('login')->with('status', 'Password reset successfully.');
}
public function loginFromMsGraph()
{
    $msUser = session('msgraph_user');

    // Lookup user in DB, validate, then log in
    $user = User::where('email', $msUser['mail'] ?? $msUser['userPrincipalName'])->first();

    if ($user) {
        Auth::login($user);
        return redirect()->route('app');
    }

    return redirect()->route('login')->withErrors(['email' => 'User not found. Please register.']);
}

}