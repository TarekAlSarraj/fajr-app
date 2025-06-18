<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        // You can pass any data needed for the user dashboard here
        return view('user.dashboard'); // Create this Blade file at resources/views/user/dashboard.blade.php
    }
}
