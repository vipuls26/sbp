<?php

namespace App\Interfaces\Payment;

interface PaymentRepositoryInterface
{
    // store payment data
    public function create(array $data);

    // create or update payment data by Stripe checkout session id
    public function updateOrCreateByStripeSessionId(string $sessionId, array $data);
}
