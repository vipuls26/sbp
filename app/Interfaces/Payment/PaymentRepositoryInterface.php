<?php

namespace App\Interfaces\Payment;

interface PaymentRepositoryInterface
{
    // store payment data
    public function create(array $data);

    // update payment data by Razorpay order id
    public function updateByOrderId(string $orderId, array $data);

    // find payment data by Razorpay order id
    public function findByOrderId(string $orderId);
}
