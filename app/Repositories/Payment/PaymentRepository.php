<?php

namespace App\Repositories\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use Override;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Store new payment data in the database.
     * This creates a row in the 'payments' table.
     * 
     * @param array $data Data array containing subscriber_id, plan_id, etc.
     */
    public function create(array $data)
    {
        return Payment::create([
            'subscriber_id' => $data['subscriber_id'], // The user paying
            'plan_id' => $data['plan_id'],             // The plan they chose
            'razor_order_id' => $data['razor_order_id'],// ID from Razorpay
            'razor_payment_id' => $data['razor_payment_id'], // Null initially
            'razor_signature' => $data['razor_signature'],   // Null initially
            'amount' => $data['amount'],               // Amount to pay
            'status' => $data['status'],               // e.g. 'pending'
            'paid_at' => $data['paid_at'],             // e.g. null initially
        ]);
    }

    /**
     * Find a specific payment using the Razorpay Order ID and update its data.
     * This is primarily used to mark a 'pending' payment as 'success' or 'failed'.
     * 
     * @param string $orderId The razor_order_id we are looking for.
     * @param array $data The fields we want to update.
     */
    public function updateByOrderId(string $orderId, array $data)
    {
        // firstOrFail() will throw a 404 error if it can't find the payment record.
        $payment = Payment::where('razor_order_id', $orderId)->firstOrFail();
        
        // Update the database row with the new data
        $payment->update($data);

        // Return the updated payment object
        return $payment;
    }

    /**
     * Retrieve a payment record using its Razorpay Order ID.
     * 
     * @param string $orderId The razor_order_id we are looking for.
     */
    public function findByOrderId(string $orderId)
    {
        // first() returns the record if found, or null if it doesn't exist.
        return Payment::where('razor_order_id', $orderId)->first();
    }
}
