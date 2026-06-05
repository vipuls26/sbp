<?php

namespace App\Interfaces\Payment;

interface PaymentRepositoryInterface
{
    // add payment
    public function create(array $data);
}
