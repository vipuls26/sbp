<?php

namespace App\Repositories\Subscription;

use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Models\Subscription;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    // add subscription
    public function create($data)
    {
        $subscription = Subscription::create($data);
        return $subscription;
    }

    // update subscription
    public function update(int $id, array $data)
    {
        $plan = Subscription::updateOrCreate(
            ['id' => $id],
            []
        );

        return $plan;
    }

    public function cancel(int $id)
    {
        return Subscription::destroy($id);
    }
}
