<?php

namespace App\Interfaces\Payment;

interface PaymentRepositoryInterface
{
    // store payment data
    public function create(array $data);
}
