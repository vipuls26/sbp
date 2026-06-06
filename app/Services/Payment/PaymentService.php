<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;

class PaymentService
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepositoryInterface
    ) {}

    // store payment data
    public function create(array $data)
    {
        return $this->paymentRepositoryInterface->create($data);
    }

    // update payment data by Razorpay order id
    public function updateByOrderId(string $orderId, array $data)
    {
        return $this->paymentRepositoryInterface->updateByOrderId($orderId, $data);
    }

    // find payment data by Razorpay order id
    public function findByOrderId(string $orderId)
    {
        return $this->paymentRepositoryInterface->findByOrderId($orderId);
    }
}
