<?php

namespace Tests\Feature;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentFlowService;
use App\Services\Stripe\StripeService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Mockery;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    public function test_create_stores_pending_payment_and_redirects_to_stripe(): void
    {
        $plan = $this->makePlan();
        $session = [
            'id' => 'cs_test_123',
            'url' => 'https://checkout.stripe.com/pay/cs_test_123',
        ];

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function (array $data) use ($plan) {
                return $data['subscriber_id'] === 7
                    && $data['plan_id'] === $plan->id
                    && $data['stripe_session_id'] === 'cs_test_123'
                    && $data['status'] === 'pending';
            }))
            ->andReturn(new Payment());

        $stripeService = Mockery::mock(StripeService::class);
        $stripeService->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturn($session);

        $subscriptionService = Mockery::mock(SubscriptionService::class);
        $paymentRepository = Mockery::mock(PaymentRepositoryInterface::class);

        $service = new PaymentFlowService(
            $paymentService,
            $subscriptionService,
            $stripeService,
            $paymentRepository
        );

        $response = $service->createCheckoutSession(
            $plan,
            7,
            'user@example.com'
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_success_marks_payment_paid_and_updates_subscription(): void
    {
        $plan = $this->makePlan();
        $sessionId = 'cs_test_456';

        $stripeSession = StripeCheckoutSession::constructFrom([
            'id' => $sessionId,
            'payment_status' => 'paid',
            'payment_intent' => 'pi_test_456',
            'customer' => 'cus_test_456',
        ]);

        $payment = new Payment();
        $payment->forceFill([
            'subscriber_id' => 7,
            'plan_id' => $plan->id,
            'stripe_session_id' => $sessionId,
            'status' => 'pending',
        ]);

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive('findBySessionId')
            ->once()
            ->with($sessionId)
            ->andReturn($payment);
        $paymentService->shouldReceive('updateBySessionId')
            ->once()
            ->with($sessionId, Mockery::on(function (array $data) {
                return $data['stripe_payment_intent_id'] === 'pi_test_456'
                    && $data['stripe_customer_id'] === 'cus_test_456'
                    && $data['status'] === 'success'
                    && $data['paid_at'] instanceof \Illuminate\Support\Carbon;
            }));

        $stripeService = Mockery::mock(StripeService::class);
        $stripeService->shouldReceive('retrieveCheckoutSession')
            ->once()
            ->with($sessionId)
            ->andReturn($stripeSession);

        $subscriptionService = Mockery::mock(SubscriptionService::class);
        $paymentRepository = Mockery::mock(PaymentRepositoryInterface::class);

        $service = new PaymentFlowService(
            $paymentService,
            $subscriptionService,
            $stripeService,
            $paymentRepository
        );

        $response = $service->handleSuccess($plan, $sessionId);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(route('user.plans'), $response->getTargetUrl());
    }

    private function makePlan(): Plan
    {
        $plan = new Plan();
        $plan->forceFill([
            'id' => 1,
            'name' => 'Starter Plan',
            'description' => 'Test plan',
            'pricing' => 199.00,
            'duration' => 'monthly',
            'is_active' => 'true',
            'admin_id' => 99,
        ]);

        return $plan;
    }
}
