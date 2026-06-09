<?php

namespace Tests\Feature;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Services\Payment\PaymentFlowService;
use App\Services\Payment\PaymentService;
use App\Services\Stripe\StripeService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    public function test_handle_marks_payment_success_and_creates_subscription(): void
    {
        $sessionId = 'cs_webhook_123';

        $payment = new Payment();
        $payment->forceFill([
            'subscriber_id' => 7,
            'plan_id' => 1,
            'stripe_session_id' => $sessionId,
            'status' => 'pending',
        ]);

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive('findBySessionId')
            ->twice()
            ->with($sessionId)
            ->andReturn($payment);
        $paymentService->shouldReceive('updateBySessionId')
            ->once()
            ->with($sessionId, Mockery::on(function (array $data) {
                return $data['stripe_payment_intent_id'] === 'pi_webhook_123'
                    && $data['stripe_customer_id'] === 'cus_webhook_123'
                    && $data['status'] === 'success'
                    && $data['paid_at'] instanceof \Illuminate\Support\Carbon;
            }));

        Config::set('services.stripe', [
            'key' => 'pk_test_key',
            'secret' => 'sk_test_key',
            'webhook_secret' => 'stripe_webhook_secret',
        ]);

        $subscriptionService = Mockery::mock(SubscriptionService::class);
        $subscriptionService->shouldReceive('update')
            ->once()
            ->with(7, 1);

        $paymentRepository = Mockery::mock(PaymentRepositoryInterface::class);
        $paymentRepository->shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function (callable $callback) {
                return $callback();
            });

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => $sessionId,
                    'payment_intent' => 'pi_webhook_123',
                    'customer' => 'cus_webhook_123',
                    'payment_status' => 'paid',
                    'metadata' => [
                        'subscriber_id' => '7',
                        'plan_id' => '1',
                    ],
                    'client_reference_id' => '7',
                ],
            ],
        ]);

        $timestamp = time();
        $signedPayload = $timestamp . '.' . $payload;
        $signature = hash_hmac('sha256', $signedPayload, 'stripe_webhook_secret');

        $request = Request::create('/api/webhooks/stripe', 'POST', [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => 't=' . $timestamp . ',v1=' . $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $payload);

        $service = new PaymentFlowService(
            $paymentService,
            $subscriptionService,
            new StripeService(),
            $paymentRepository
        );

        $response = $service->handleWebhook($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ], $response->getData(true));
    }
}
