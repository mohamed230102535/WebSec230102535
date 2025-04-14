<?php

namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;


class PagesController extends Controller
{
    /**
     * Display authenticated user's Microsoft Graph data.
     */
    public function app()
    {
        return view('welcome');
    }
}