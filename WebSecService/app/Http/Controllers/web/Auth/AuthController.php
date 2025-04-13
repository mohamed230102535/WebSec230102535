<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
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
     * Redirect to GitHub OAuth.
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->stateless()->redirect();
    }

    /**
     * Handle GitHub callback and redirect to app.
     */
    public function handleGithubCallback(Request $request)
    {
        try {
            if ($request->has('error')) {
                throw new \Exception($request->error_description ?? $request->error);
            }

            $githubUser = Socialite::driver('github')->stateless()->user();
            
            if (empty($githubUser->email)) {
                throw new \Exception('No email provided by GitHub');
            }
            
            $user = User::updateOrCreate(
                ['email' => $githubUser->email],
                [
                    'name' => $githubUser->name ?? $githubUser->nickname ?? 'GitHub User',
                    'password' => bcrypt(Str::random(16)),
                    'credit' => 0,
                    'github_id' => $githubUser->id
                ]
            );
            
            Auth::login($user, true);
            $request->session()->regenerate();
            
            return redirect()->intended(route('app'))->with('success', 'Logged in successfully');
            
        } catch (\Exception $e) {
            \Log::error('GitHub login error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return redirect()->route('login')
                ->withErrors(['error' => 'GitHub login failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Logout the user.
     */
    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }
}
