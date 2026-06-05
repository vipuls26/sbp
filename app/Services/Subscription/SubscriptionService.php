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
    public function create(Plan $plan)
    {
        $user = Auth::user();
        $startDate = now()->toDateTimeString();
        $endDate = $plan->duration === 'annual'
            ? now()->addYear()->toDateTimeString()
            : now()->addDays(30)->toDateTimeString();

        $subscription = $this->subscriptionRepositoryInterface->create(
            [
                'plan_id' => $plan->id,
                'subscriber_id' => $user->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        );

        return $subscription;
    }

    // update subscription
    public function update(int $subscriberId, int $planId)
    {
        $plan = Plan::findOrFail($planId);

        // update subscription data
        $data = [
            'plan_id' => $plan->id,
            'start_date' => now()->toDateTimeString(),
            'end_date' => $plan->duration === 'annual'
                ? now()->addYear()->toDateTimeString()
                : now()->addDays(30)->toDateTimeString(),
        ];

        $subscription = $this->subscriptionRepositoryInterface->update($subscriberId, $data);

        return $subscription;
    }

    // cancel subscription
    public function cancel(int $subscriberId)
    {
        return $this->subscriptionRepositoryInterface->cancel($subscriberId);
    }
}
