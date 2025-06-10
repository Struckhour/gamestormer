<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Example: to list users

class DashboardController extends Controller
{
    public function index()
    {
        // Example: Fetch some data for the admin dashboard
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();

        return view('admin.dashboard', compact('totalUsers', 'adminUsers'));
    }

    // Example: Method to manage users (you'd expand on this)
    public function users()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }
}
