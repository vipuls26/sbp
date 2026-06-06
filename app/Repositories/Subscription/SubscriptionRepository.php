<?php

namespace App\Repositories\Subscription;

use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Models\Subscription;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    // add subscription
    public function create(array $data)
    {
        $subscription = Subscription::create($data);
        return $subscription;
    }

    // update subscription
    public function update(int $subscriberId, array $data)
    {
        $subscription = Subscription::updateOrCreate(
            ['subscriber_id' => $subscriberId],
            $data
        );

        return $subscription;
    }

    public function cancel(int $subscriberId)
    {
        return Subscription::where('subscriber_id', $subscriberId)->delete();
    }
}
