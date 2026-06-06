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
     * This is usually called right before we open the Razorpay popup.
     * 
     * @param array $data The payment details like amount, plan_id, etc.
     */
    public function create(array $data)
    {
        return $this->paymentRepositoryInterface->create($data);
    }

    /**
     * Update an existing payment record using the Razorpay Order ID.
     * This is useful when Razorpay responds back after a success or failure,
     * so we can update the status (e.g. from 'pending' to 'success').
     * 
     * @param string $orderId The Razorpay order ID we got earlier.
     * @param array $data The new data to update (like signature, payment id).
     */
    public function updateByOrderId(string $orderId, array $data)
    {
        return $this->paymentRepositoryInterface->updateByOrderId($orderId, $data);
    }

    /**
     * Find a payment record by its Razorpay Order ID.
     * We use this to check if a pending payment actually exists 
     * before we try to verify the final payment.
     * 
     * @param string $orderId The Razorpay order ID.
     */
    public function findByOrderId(string $orderId)
    {
        return $this->paymentRepositoryInterface->findByOrderId($orderId);
    }
}
