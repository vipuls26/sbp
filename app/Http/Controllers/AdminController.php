<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Admin\AdminService;

class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    public function index()
    {
        $users = User::withTrashed()->userOnly()->count();
        $blockedUsers = User::onlyTrashed()->userOnly()->count();
        $subscriptions = Subscription::with('plan', 'user')->latest()->get();
        $plans = Plan::withTrashed()->get()->count();

        // total earning
        $totalEarning = Subscription::totalEarning()->value('total_earning') ?? 0;

        return view('admin.dashboard', compact('users', 'blockedUsers', 'plans', 'subscriptions', 'totalEarning'));
    }
    public function allUser()
    {
        $users = User::withTrashed()->userOnly()->get();
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
        $subscribers = Subscription::with('plan', 'user')->get();
        return view('admin.subscriber', compact('subscribers'));
    }
}
