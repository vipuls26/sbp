<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private SubscriptionService $subscriptionService
    ) {}

    public function handle(Request $request)
    {
        Log::info('Webhook executed');

        $payload = $request->getContent();
        $receivedSignature = (string) $request->header('X-Razorpay-Signature');
        $webhookSecret = config('services.razorpay.webhook_secret');

        // webhook secrect is missing
        if (! $webhookSecret) {
            Log::warning('Razorpay webhook secret is missing.');

            return response()->json([
                'success' => false,
                'message' => 'Webhook secret is not configured.',
            ], 500);
        }

        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        if (! hash_equals($expectedSignature, $receivedSignature)) {
            Log::warning('Razorpay webhook signature mismatch.');

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature.',
            ], 400);
        }

        $data = json_decode($payload, true);

        Log::info('payload', $data);

        if (! is_array($data)) {
            Log::warning('Razorpay webhook payload is not valid JSON.');

            return response()->json([
                'success' => false,
                'message' => 'Invalid payload.',
            ], 400);
        }

        $event = $data['event'] ?? null;
        $paymentData = data_get($data, 'payload.payment.entity');

        if (! is_array($paymentData)) {
            Log::warning('Razorpay webhook payload does not contain payment data.', [
                'event' => $event,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event ignored.',
            ]);
        }

        $orderId = $paymentData['order_id'] ?? null;
        $paymentId = $paymentData['id'] ?? null;
        $status = $paymentData['status'] ?? null;

        if (! $orderId || ! $paymentId) {
            Log::warning('Razorpay webhook payment data is missing order or payment id.', [
                'event' => $event,
                'payment_id' => $paymentId,
                'order_id' => $orderId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Required payment fields are missing.',
            ], 400);
        }

        $payment = $this->paymentService->findByOrderId($orderId);

        if (! $payment) {
            Log::warning('Razorpay webhook payment record was not found.', [
                'event' => $event,
                'order_id' => $orderId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment record not found. Event ignored.',
            ]);
        }

        if ($event === 'payment.failed') {
            if ($payment->status === 'success') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment already processed successfully.',
                ]);
            }

            $this->paymentService->updateByOrderId($orderId, [
                'razor_payment_id' => $paymentId,
                'status' => 'failed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment marked as failed.',
            ]);
        }

        if (! in_array($event, ['payment.captured', 'order.paid'], true)) {
            return response()->json([
                'success' => true,
                'message' => 'Event ignored.',
            ]);
        }

        if ($payment->status === 'success') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already processed successfully.',
            ]);
        }

        DB::transaction(function () use ($orderId, $paymentId) {
            $this->paymentService->updateByOrderId($orderId, [
                'razor_payment_id' => $paymentId,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            // Keep the subscription in sync with the payment record, not the route.
            $payment = $this->paymentService->findByOrderId($orderId);

            if ($payment) {
                $this->subscriptionService->update((int) $payment->subscriber_id, (int) $payment->plan_id);
            }
        });

        Log::info('Razorpay webhook payment processed successfully.', [
            'event' => $event,
            'order_id' => $orderId,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ]);
    }
}
