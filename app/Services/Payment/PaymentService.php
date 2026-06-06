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
}
