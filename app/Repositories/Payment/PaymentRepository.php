<?php

namespace App\Repositories\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{

    // store payment data in the database.
    public function create(array $data)
    {
        return Payment::create([
            'subscriber_id' => $data['subscriber_id'],
            'plan_id' => $data['plan_id'],
            'razor_order_id' => $data['razor_order_id'],
            'razor_payment_id' => $data['razor_payment_id'],
            'razor_signature' => $data['razor_signature'],
            'amount' => $data['amount'],
            'status' => $data['status'],
            'paid_at' => $data['paid_at'],
        ]);
    }

    // find record by id and update it status
    public function updateByOrderId(string $orderId, array $data)
    {
        $payment = Payment::where('razor_order_id', $orderId)->firstOrFail();
        $payment->update($data);
        return $payment;
    }

    // fetch a payment record using its Razorpay Order ID.
    public function findByOrderId(string $orderId)
    {
        return Payment::where('razor_order_id', $orderId)->first();
    }
}
