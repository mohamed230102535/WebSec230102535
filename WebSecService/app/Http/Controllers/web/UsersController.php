<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
use App\Traits\EmailHelper;

class UsersController extends Controller
{
    use EmailHelper;
    use ValidatesRequests;

    public function list(Request $request) {
        if (!auth()->user()->hasPermissionTo('show_users')) {
            abort(401);
        }
    
        $query = User::select('*');
    
        // If the user is an employee, only show customers
        if (auth()->user()->hasRole('Employee')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'customer'); // assuming 'customer' is the role name for customers
            });
        }
    
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

        $user->assignRole('Customer'); // Assign default role

        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        $this->sendSimpleEmail($user->email, 'Email Verification', 'emails.verification', ['link' => $link, 'name' => $request->name]);

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

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {

        $user = User::where('email', $request->email)->first();
        if(!$user->email_verified_at)
        return redirect()->back()->withInput($request->input())
        ->withErrors('Your email is not verified.');

    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
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
    

        if(auth()->user()->hasPermissionTo('admin_users')) {
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
    
           
            Artisan::call('cache:clear');
        }
    
        return redirect(route('profile', ['user' => $user->id]));
    }
    

    public function delete(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);

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
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }


public function create(Request $request) {
    if (!auth()->user()->hasPermissionTo('admin_users')) abort(401);

    $roles = Role::all();
    $permissions = Permission::all();

    return view('users.add', compact('roles', 'permissions'));
}

public function store(Request $request) {
    if (!auth()->user()->hasPermissionTo('admin_users')) abort(401);

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


    $this->sendSimpleEmail($user->email, 'Reset Password', 'emails.ResetForm', ['link' => $link]);

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