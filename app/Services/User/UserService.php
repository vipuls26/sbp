<?php

namespace App\Services\User;

use App\Models\Plan;
use App\Models\Subscription;

class UserService
{
    public function dashboard():array
    {
        return [
            'plans' => Plan::where('is_active', 'true')->get(),
            'subscription' => Subscription::activeSubscription()->first(),
        ];
    }
}
