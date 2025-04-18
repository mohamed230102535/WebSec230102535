<?php

namespace App\Listeners;

use App\Models\User;
use Dcblogdev\MsGraph\MsGraph;
use Illuminate\Support\Facades\Auth;

class NewMicrosoft365SignInListener
{
    public function handle(object $event): void
    {
        $user = User::firstOrCreate([
            'email' => $event->token['info']['mail'],
        ], [
            'name' => $event->token['info']['displayName'],
            'email' => $event->token['info']['mail'] ?? $event->token['info']['userPrincipalName'],
            'password' => '',
        ]);

        try {
            logger()->info('Storing token for user:', ['user_id' => $user->id, 'email' => $user->email]);
            
            (new MsGraph)->storeToken(
                $event->token['accessToken'],
                $event->token['refreshToken'],
                $event->token['expires'],
                $user->id,
                $user->email
            );
            
            logger()->info('Token stored successfully');
        } catch (\Exception $e) {
            logger()->error('Failed to store token:', ['error' => $e->getMessage()]);
            throw $e;
        }

        Auth::login($user);
    }
}
