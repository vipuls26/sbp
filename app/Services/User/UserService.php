<?php

namespace App\Services\User;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;

class UserService
{
    public function dashboard():array
    {
        return [
            'plans' => Plan::where('is_active', 'true')->get(),
            // Show the latest subscription row so users can see their current plan.
            'subscription' => Subscription::where('user_id', auth()->id())
                ->latest()
                ->first(),
            // Show the latest payment so the user can download the most recent invoice.
            'latestPayment' => Payment::where('user_id', auth()->id())
                ->latest()
                ->first(),
        ];
    }
}
