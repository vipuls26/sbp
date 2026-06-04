<?php

namespace App\Services\Subscription;

use App\Interfaces\Subscription\SubscriptionRepositoryInterface;

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
                'subscriber_id' => auth()->id(),
                'start_date' => now(),
                'end_date' => $data['duration'] == 'annual' ? now()->addYear() : now()->addDays(30),
            ]
        );
    }

    // update subscription
    public function update(int $id, $data)
    {
        return $this->subscriptionRepositoryInterface->update($id, $data);
    }

    // cancel subscription
    public function cancel(int $id)
    {
        return $this->subscriptionRepositoryInterface->cancel($id);
    }
}
