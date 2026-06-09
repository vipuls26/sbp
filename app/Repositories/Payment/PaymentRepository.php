<?php

namespace App\Repositories\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{

    // store payment data in the database.
    public function create(array $data)
    {
        return Payment::create($data);
    }

    // create or update a payment record by checkout session id.
    public function updateOrCreateByStripeSessionId(string $sessionId, array $data)
    {
        return Payment::updateOrCreate(
            ['stripe_checkout_session_id' => $sessionId],
            $data
        );
    }
}
