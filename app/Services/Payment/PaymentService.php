<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;

class PaymentService
{
    // This service keeps Stripe payment persistence in one place.
    public function __construct(
        private PaymentRepositoryInterface $paymentRepositoryInterface
    ) {}

    /**
     * Create a payment record in the database.
     * The record is usually created after Stripe confirms the checkout session.
     */
    public function create(array $data)
    {
        return $this->paymentRepositoryInterface->create($data);
    }

    /**
     * Create or update a payment record for a Stripe checkout session.
     */
    public function updateOrCreateByStripeSessionId(string $sessionId, array $data)
    {
        return $this->paymentRepositoryInterface->updateOrCreateByStripeSessionId($sessionId, $data);
    }

}
