<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Admin\AdminService;

class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    public function index()
    {
        $dashboard = $this->adminService->dashboard();
        return view('admin.dashboard', $dashboard);
    }

    public function users()
    {
        $users = $this->adminService->users();
        return view('admin.users', compact('users'));
    }

    // block user
    public function blockUser(User $user)
    {
        $this->adminService->block($user->id);
        return redirect()->route('admin.dashboard')->with('success', 'User Block Successfully');
    }

    // unblock user
    public function restoreUser(User $user)
    {
        $this->adminService->restore($user->id);
        return redirect()->route('admin.dashboard')->with('success', 'User Unblock Successfully');
    }

    // subscriber
    public function subscribers()
    {
        $subscribers = $this->adminService->subscribers();
        return view('admin.subscriber', compact('subscribers'));
    }
}
