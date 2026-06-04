<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Admin\AdminService;

class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    public function index()
    {
        // $users = User::get();
        $users = User::userOnly()->get();
        return view('admin.dashboard', compact('users'));
    }

    // block user
    public function block(User $user)
    {
        $this->adminService->block($user->id);
        return redirect()->route('admin.dashboard')->with('success', 'User Block Successfully');
    }
}
