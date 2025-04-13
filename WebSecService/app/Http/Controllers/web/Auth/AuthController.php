<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Dcblogdev\MsGraph\Facades\MsGraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function login()
    {
        return view('users.login');
    }

    /**
     * Redirect to Microsoft OAuth.
     */
    public function connect()
    {
        return MsGraph::connect(); 
    }

    /**
     * Handle Microsoft callback and redirect to decision page.
     */
    public function callback()
    {
        try {
            // This exchanges the code for tokens (must only run ONCE)
            MsGraph::connect();
    
            // Optional: get and store user info
            $msUser = MsGraph::get();
            session(['ms_user' => $msUser]);
    
            // Redirect immediately to avoid refresh issues
            return redirect()->route('msgraph.decision');
    
        } catch (\Exception $e) {
            // Handle gracefully
            return redirect('/login')->with('error', 'Microsoft login failed: ' . $e->getMessage());
        }
    }
    
    

    

    /**
     * Show decision page after Microsoft login.
     */
    public function decision()
    {
        return view('users.decision'); // blade view with login/register options
    }

    /**
     * Log user in from Microsoft info.
     */
    public function loginFromMsGraph(Request $request)
    {
        $msUser = MsGraph::get();
        $user = User::where('email', $msUser['mail'] ?? $msUser['userPrincipalName'])->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('app');
        }

        return redirect()->route('login')->withErrors('User not found.');
    }

    /**
     * Register a new user from Microsoft info.
     */
    public function registerFromMsGraph(Request $request)
    {
        $msUser = MsGraph::get();
        $email = $msUser['mail'] ?? $msUser['userPrincipalName'];

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            return redirect()->route('login')->withErrors('User already exists.');
        }

        // Create and log in the user
        $user = User::create([
            'name' => $msUser['displayName'],
            'email' => $email,
            // You might store additional info or generate a password/token
        ]);

        Auth::login($user);
        return redirect()->route('app');
    }

    /**
     * Logout and revoke Microsoft tokens.
     */
    public function logout()
    {
        MsGraph::disconnect(); // Revoke tokens first
        auth()->logout();      // Laravel logout
        return redirect('/');  // Redirect to home
    }
}
