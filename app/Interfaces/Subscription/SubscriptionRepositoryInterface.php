<?php

namespace App\Interfaces\Subscription;

interface SubscriptionRepositoryInterface
{
    // add subscription
    public function create(array $data);

    // update subscription
    public function update(int $userId, array $data);

    // cancel subscription
    public function cancel(int $userId, array $data = []);
}
