<?php

namespace App\Interfaces\Payment;

interface PaymentRepositoryInterface
{
    // add payment
    public function checkout(array $data);
}
