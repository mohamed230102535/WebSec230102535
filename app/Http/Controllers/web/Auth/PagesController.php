<?php

namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;

use Dcblogdev\MsGraph\Facades\MsGraph; // Fixed namespace


class PagesController extends Controller
{
    /**
     * Display authenticated user's Microsoft Graph data.
     */
    public function app()
    {
        // Fetch user profile from Microsoft Graph
       dd(MsGraph::get('me'));

        // Return view with user data (remove dd() for production)
        return view('welcome', [
            'user' => $user
        ]);
    }
}