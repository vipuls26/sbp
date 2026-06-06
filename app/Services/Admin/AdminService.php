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

            'subscriptions' => Subscription::with('plan', 'subscriber')
                ->latest()
                ->get(),

            'plans' => Plan::withTrashed()
                ->count(),

            'totalEarning' => Subscription::totalEarning()
                ->value('total_earning') ?? 0,
        ];
    }

    // all users
    public function users()
    {
        return User::withTrashed()->userOnly()->get();
    }

    // subscribers
    public function subscribers()
    {
        return Subscription::with('plan', 'subscriber')->get();
    }


    // block user
    public function block(int $id)
    {
        return $this->adminRepository->block($id);
    }

    // restore a blocked user
    public function restore(int $id)
    {
        return $this->adminRepository->restore($id);
    }
}
