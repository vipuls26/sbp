<?php

namespace App\Interfaces\Payment;

interface PaymentRepositoryInterface
{
    // store payment data
    public function create(array $data);

    // update payment data by Stripe checkout session id
    public function updateBySessionId(string $sessionId, array $data);

    // find payment data by Stripe checkout session id
    public function findBySessionId(string $sessionId);

    // run payment related changes inside a single database transaction
    public function transaction(callable $callback);
}
