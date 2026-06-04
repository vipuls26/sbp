<?php

namespace App\Services\Subscription;

use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class SubscriptionService
{
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepositoryInterface
    ) {}

    // create subscription
    public function create($data)
    {
        // dd($data);
        return $this->subscriptionRepositoryInterface->create(
            [
                'plan_id' => $data['id'],
                'subscriber_id' => Auth::user()->id,
                'start_date' => now(),
                'end_date' => $data['duration'] == 'annual' ? now()->addYear() : now()->addDays(30),
            ]
        );
    }

    // update subscription
    public function update(int $subscriberId, int $planId)
    {
        $plan = Plan::findOrFail($planId);

        // dd($plan->id);

        // update subscription data
        $data = [
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => $plan->duration === 'annual' ? now()->addYear() : now()->addDays(30),
        ];

        return $this->subscriptionRepositoryInterface->update($subscriberId, $data);
    }

    // cancel subscription
    public function cancel(int $subscriberId)
    {
        return $this->subscriptionRepositoryInterface->cancel($subscriberId);
    }
}
