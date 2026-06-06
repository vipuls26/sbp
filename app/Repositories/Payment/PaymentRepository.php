<?php

namespace App\Repositories\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use Override;

class PaymentRepository implements PaymentRepositoryInterface
{
    // store payment data
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

    // update payment data by Razorpay order id
    public function updateByOrderId(string $orderId, array $data)
    {
        $payment = Payment::where('razor_order_id', $orderId)->firstOrFail();
        $payment->update($data);

        return $payment;
    }

    // find payment data by Razorpay order id
    public function findByOrderId(string $orderId)
    {
        return Payment::where('razor_order_id', $orderId)->first();
    }
}
