<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;

class PaymentService
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepositoryInterface
    ) {}

    // add payment data in db
    public function create(array $data)
    {
        return $this->paymentRepositoryInterface->checkout($data);
    }
}
