<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // You can pass any data needed for the admin dashboard here
        return view('admin.dashboard'); // Create this Blade file at resources/views/admin/dashboard.blade.php
    }
}
