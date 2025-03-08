<?php
namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use App\Models\UsersDB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

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
        return redirect()-> route('WebAuthentication.index');  // it treats as url without ->route
    }

    public function doLogin(Request $request) {
        // $this->validate($request, [
        //     'email' => 'required|email',
        //     'password' => 'required|min:6'
        // ]);
    
        // if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     return redirect()->back()->withInput($request->only('email'))->withErrors(['Invalid login information.']);
        // }
    
        return redirect()->route('WebAuthentication.index');
    }
    public function doRegister(   Request $request){

        // if ($request->password != $request->password_confirmation) {
        //     return redirect()->route('WebAuthentication.register',[error => 'Password does not match.']);
        // }
        // if (!$request->name || !$request->email || !$request->password) {
        //     return redirect()->route('WebAuthentication.register',[error => 'All fields are required.']);
        // }
        // if (UserDB::where('email', $request->email)->exists()) {
        //     return redirect()->route('WebAuthentication.register',[error => 'Invalid registration attempt.']);
        // }

        $this->validate($request,[

            'name' => 'required|string|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new UsersDB();
        $user->fullname= $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();


        return redirect()-> route('WebAuthentication.login');
    }
}