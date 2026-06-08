<?php

namespace App\Services\Razorpay;

use Razorpay\Api\Api;

class RazorpayApiService
{
    public function createOrder(array $data): array
    {
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
        return $api->order->create($data)->toArray();
    }

    public function verifyPaymentSignature(array $data): void
    {
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $data['razorpay_order_id'],
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature' => $data['razorpay_signature'],
        ]);
    }
}
