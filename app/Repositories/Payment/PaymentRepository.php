<?php

namespace App\Repositories\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentRepository implements PaymentRepositoryInterface
{
    // store payment data in the database.
    public function create(array $data)
    {
        return Payment::create([
            'subscriber_id' => $data['subscriber_id'],
            'plan_id' => $data['plan_id'],
            'stripe_session_id' => $data['stripe_session_id'],
            'stripe_payment_intent_id' => $data['stripe_payment_intent_id'],
            'stripe_customer_id' => $data['stripe_customer_id'],
            'amount' => $data['amount'],
            'status' => $data['status'],
            'paid_at' => $data['paid_at'],
        ]);
    }

    // find record by checkout session id and update it
    public function updateBySessionId(string $sessionId, array $data)
    {
        $payment = Payment::where('stripe_session_id', $sessionId)->firstOrFail();
        $payment->update($data);
        return $payment;
    }

    // fetch a payment record using its Stripe checkout session id.
    public function findBySessionId(string $sessionId)
    {
        return Payment::where('stripe_session_id', $sessionId)->first();
    }

    // run payment related changes inside a single database transaction
    public function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }
}
