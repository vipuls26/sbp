<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;

class PaymentService
{
    // We inject the Repository interface here. 
    // This connects our Service (which handles business logic) 
    // to our Repository (which handles database operations).
    public function __construct(
        private PaymentRepositoryInterface $paymentRepositoryInterface
    ) {}

    /**
     * Create a new payment record in the database.
     * This is called before we redirect the user to Stripe Checkout.
     * 
     * @param array $data The payment details like amount, plan_id, etc.
     */
    public function create(array $data)
    {
        return $this->paymentRepositoryInterface->create($data);
    }

    /**
     * Update an existing payment record using the Stripe Checkout session id.
     * 
     * @param string $sessionId The Stripe Checkout session id we got earlier.
     * @param array $data The new data to update (like signature, payment id).
     */
    public function updateBySessionId(string $sessionId, array $data)
    {
        return $this->paymentRepositoryInterface->updateBySessionId($sessionId, $data);
    }

    /**
     * Find a payment record by its Stripe Checkout session id.
     * We use this to check if a pending payment actually exists
     * before we try to verify the final payment.
     * 
     * @param string $sessionId The Stripe Checkout session id.
     */
    public function findBySessionId(string $sessionId)
    {
        return $this->paymentRepositoryInterface->findBySessionId($sessionId);
    }
}
