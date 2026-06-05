<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\AdminRepositoryInterface;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class AdminService
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {}

    // dashboard
    public function dashboard(): array
    {
        return [
            'users' => User::withTrashed()
                ->userOnly()
                ->count(),

            'blockedUsers' => User::onlyTrashed()
                ->userOnly()
                ->count(),

            'subscriptions' => Subscription::with('plan', 'user')
                ->latest()
                ->get(),

            'plans' => Plan::withTrashed()
                ->count(),

            'totalEarning' => Subscription::totalEarning()
                ->value('total_earning') ?? 0,
        ];
    }

    // all user
    public function totalUser()
    {
        $users = User::withTrashed()->userOnly()->get();
        return $users;
    }

    // subscriber
    public function totalSubscriber()
    {
        $subscribers = Subscription::with('plan', 'user')->get();
        return $subscribers;
    }


    // block user
    public function block(int $id)
    {
        return $this->adminRepository->block($id);
    }

    // unblock user
    public function unblock(int $id)
    {
        return $this->adminRepository->unblock($id);
    }

    // subscription detail
    public function subscriber()
    {
        return Subscription::with(['plan', 'user'])->get();
    }
}
