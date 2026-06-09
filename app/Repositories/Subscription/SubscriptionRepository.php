<?php

namespace App\Repositories\Subscription;

use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Models\Subscription;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    // add subscription
    public function create(array $data)
    {
        return Subscription::create($data);
    }

    // update subscription
    public function update(int $userId, array $data)
    {
        $subscription = Subscription::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        return $subscription;
    }

    // cancel subscription
    public function cancel(int $userId, array $data = [])
    {
        $subscription = Subscription::where('user_id', $userId)->first();

        if (! $subscription) {
            return null;
        }

        $subscription->update($data);

        return $subscription;
    }
}
