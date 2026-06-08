<?php

namespace Tests\Feature;

use App\Http\Controllers\WebhookController;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\Payment\PaymentService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    public function test_handle_marks_payment_success_and_creates_subscription(): void
    {
        $orderId = 'order_webhook_123';
        $paymentId = 'pay_webhook_123';
        $secret = 'webhook_test_secret';

        Config::set('services.razorpay.webhook_secret', $secret);

        $payment = new Payment();
        $payment->forceFill([
            'subscriber_id' => 7,
            'plan_id' => 1,
            'razor_order_id' => $orderId,
            'razor_payment_id' => null,
            'razor_signature' => null,
            'amount' => 299.00,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        $payload = [
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => $paymentId,
                        'order_id' => $orderId,
                        'status' => 'captured',
                    ],
                ],
            ],
        ];

        $rawPayload = json_encode($payload);
        $signature = hash_hmac('sha256', $rawPayload, $secret);

        $request = Request::create('/api/webhooks/razorpay', 'POST', [], [], [], [
            'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $rawPayload);

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive('findByOrderId')
            ->twice()
            ->with($orderId)
            ->andReturn($payment);
        $paymentService->shouldReceive('updateByOrderId')
            ->once()
            ->with($orderId, Mockery::on(function (array $data) use ($paymentId) {
                return $data['razor_payment_id'] === $paymentId
                    && $data['status'] === 'success'
                    && $data['paid_at'] instanceof \Illuminate\Support\Carbon;
            }));

        $subscriptionService = Mockery::mock(SubscriptionService::class);
        $subscriptionService->shouldReceive('update')
            ->once()
            ->with(7, 1);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function (callable $callback) {
                return $callback();
            });

        $controller = new WebhookController($paymentService, $subscriptionService);

        $response = $controller->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ], $response->getData(true));
    }
}
