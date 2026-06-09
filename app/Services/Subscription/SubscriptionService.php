<?php

namespace App\Services\Subscription;

use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Models\Plan;
class SubscriptionService
{
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepositoryInterface
    ) {}

    // update subscription
    public function update(int $userId, int $planId, array $extra = [])
    {
        $plan = Plan::findOrFail($planId);

        if ($plan->is_active !== 'true') {
            abort(404);
        }

        // update subscription data
        $data = [
            'user_id' => $userId,
            'plan_id' => $plan->id,
            'subscriber_id' => $userId,
            'type' => 'default',
            'stripe_price' => $plan->stripe_price_id,
            'stripe_status' => $extra['stripe_status'] ?? 'active',
            'quantity' => $extra['quantity'] ?? 1,
            'stripe_id' => $extra['stripe_id'] ?? null,
            'start_date' => $extra['start_date'] ?? now()->toDateTimeString(),
            'end_date' => $extra['end_date'] ?? ($plan->duration === 'annual'
                ? now()->addYear()->toDateTimeString()
                : now()->addDays(30)->toDateTimeString()),
            'status' => $extra['status'] ?? 'active',
        ];

        $subscription = $this->subscriptionRepositoryInterface->update($userId, $data);

        return $subscription;
    }

    // cancel subscription
    public function cancel(int $userId, array $extra = [])
    {
        return $this->subscriptionRepositoryInterface->cancel($userId, $extra);
    }
}
