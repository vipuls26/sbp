<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Razorpay webhook hit');
        Log::info('Webhook method: ' . $request->method());
        Log::info('Webhook url: ' . $request->fullUrl());

        $payload = $request->getContent();
        $receivedSignature = $request->header('X-Razorpay-Signature');
        $webhookSecret = config('services.razorpay.webhook_secret');

        if (! $webhookSecret) {
            Log::warning('Razorpay webhook secret is missing.');

            return response()->json([
                'success' => false,
                'message' => 'Webhook secret is not configured.',
            ], 500);
        }

        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        if (! hash_equals($expectedSignature, (string) $receivedSignature)) {
            Log::warning('Razorpay webhook signature mismatch.');
            Log::warning('Received signature: ' . (string) $receivedSignature);

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature.',
            ], 400);
        }

        Log::info('Razorpay webhook received successfully.');
        Log::info($request->all());

        return response()->json([
            'success' => true,
        ]);
    }
}
