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

class AuthController extends Controller{
    use ValidatesRequests;

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

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())->withErrors(['Invalid login information.']);
        }

        // Check user role and redirect accordingly
        if (Auth::user()->role === 'admin') {
            return redirect()->route('WebAuthentication.dashboard');
        } else {
            return redirect()->route('WebAuthentication.index');
        }
    }
    public function doRegister(Request $request){
        // Validate the request
        $this->validate($request,[
            'name' => 'required|string|min:5',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'This email is already registered. Please login or reset your password.']);
        }

        // Create new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user'
        ]);

        return redirect()->route('WebAuthentication.login')
            ->with('success', 'Registration successful! Please login.');
    }


//============================================================================================================
    public function userAccount(Request $request){
        return view('WebAuthentication.userAccount');
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

    // Shows the forgot password form view
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

            // Then validate password
            $this->validate($request, [
                'new_password' => 'required|min:6|confirmed',
            ]);

            // Check if the email matches the currently logged in user
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
    $users = User::all();
    return view('WebAuthentication.dashboard', compact('users'));
}
public function showUser($id) {
    $user = User::findOrFail($id);
    return view('WebAuthentication.users.show', compact('user')); 
}

// Edit User
public function editUser($id) // $id is the user ID from the database
{
    // findOrFail will find user with matching ID or throw 404 if not found
    $user = User::findOrFail($id); 
    return view('WebAuthentication.users.edit', compact('user'));
}
// Create User
public function createUser()
{
    return view('WebAuthentication.users.create');
}

// Store User
public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,user'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role
    ]);

    return redirect()->route('WebAuthentication.dashboard')
        ->with('success', 'User created successfully');
}


// Update User 
public function updateUser(Request $request, $id) // $id is user ID to update
{
    // Find user by ID or throw 404
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email,' , 
        'role' => 'required|in:admin,user'
    ]);

    $userData = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role
    ];

    if ($request->filled('password')) {
        $userData['password'] = bcrypt($request->password);
    }

    $user->update($userData);

    return redirect()->route('WebAuthentication.dashboard')
        ->with('success', 'User updated successfully');
}

// Delete User
public function deleteUser($id) // $id is user ID to delete
{
    // Find user by ID or throw 404
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('WebAuthentication.dashboard')
        ->with('success', 'User deleted successfully');
}
}