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

    public function allUser()
    {
        $users = $this->adminService->totalUser();
        return view('admin.users', compact('users'));
    }

    // block user
    public function block(User $user)
    {
        $this->adminService->block($user->id);
        return redirect()->route('admin.dashboard')->with('success', 'User Block Successfully');
    }

    // unblock user
    public function unblock(User $user)
    {
        $this->adminService->unblock($user->id);
        return redirect()->route('admin.dashboard')->with('success', 'User Unblock Successfully');
    }

    // subscriber
    public function subscriber()
    {
        $subscribers = $this->adminService->totalSubscriber();
        return view('admin.subscriber', compact('subscribers'));
    }
}
