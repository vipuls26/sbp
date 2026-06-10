<?php

namespace App\Services\User;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function dashboard(): array
    {
        return [
            'monthlyPlans' => Plan::where('is_active', 'true')->where('duration', 'monthly')->get(),
            'annualPlans' => Plan::where('is_active', 'true')->where('duration', 'annual')->get(),
            'plans' => Plan::where('is_active', 'true')->get(),
            // Show the latest subscription row so users can see their current plan.
            'subscription' => Subscription::where('user_id', Auth::id())
                ->whereIn('status', ['active', 'trialing'])
                ->latest()
                ->first(),
            // Show the latest payment so the user can download the most recent invoice.
            'latestPayment' => Payment::where('user_id', Auth::id())
                ->latest()
                ->first(),
        ];
    }

    public function myPlan()
    {
        return Plan::where('id', Auth::user()->subscription->plan_id)->first();
    }
}
