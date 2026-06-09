<?php

namespace Tests\Feature;

use App\Interfaces\Auth\UserRepositoryInterface;
use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentFlowService;
use App\Services\Stripe\StripeService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Mockery;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    public function test_create_stores_pending_payment_and_redirects_to_stripe(): void
    {
        $plan = $this->makePlan();
        $user = $this->makeUser(null);
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
        $stripeService->shouldReceive('createCustomer')
            ->once()
            ->with('Test User', 'user@example.com')
            ->andReturn('cus_test_123');
        $stripeService->shouldReceive('createCheckoutSession')
            ->once()
            ->with($plan, 7, 'cus_test_123', Mockery::type('string'), Mockery::type('string'))
            ->andReturn($session);

        $subscriptionService = Mockery::mock(SubscriptionService::class);
        $paymentRepository = Mockery::mock(PaymentRepositoryInterface::class);
        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('updateStripeCustomerId')
            ->once()
            ->with(7, 'cus_test_123');

        $service = new PaymentFlowService(
            $paymentService,
            $subscriptionService,
            $stripeService,
            $paymentRepository,
            $userRepository
        );

        $response = $service->createCheckoutSession(
            $plan,
            $user
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_create_reuses_existing_stripe_customer_id(): void
    {
        $plan = $this->makePlan();
        $user = $this->makeUser('cus_existing_123');
        $session = [
            'id' => 'cs_test_456',
            'url' => 'https://checkout.stripe.com/pay/cs_test_456',
        ];

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function (array $data) use ($plan) {
                return $data['subscriber_id'] === 7
                    && $data['plan_id'] === $plan->id
                    && $data['stripe_session_id'] === 'cs_test_456'
                    && $data['status'] === 'pending';
            }))
            ->andReturn(new Payment());

        $stripeService = Mockery::mock(StripeService::class);
        $stripeService->shouldNotReceive('createCustomer');
        $stripeService->shouldReceive('createCheckoutSession')
            ->once()
            ->with($plan, 7, 'cus_existing_123', Mockery::type('string'), Mockery::type('string'))
            ->andReturn($session);

        $subscriptionService = Mockery::mock(SubscriptionService::class);
        $paymentRepository = Mockery::mock(PaymentRepositoryInterface::class);
        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldNotReceive('updateStripeCustomerId');

        $service = new PaymentFlowService(
            $paymentService,
            $subscriptionService,
            $stripeService,
            $paymentRepository,
            $userRepository
        );

        $response = $service->createCheckoutSession($plan, $user);

        $this->assertInstanceOf(RedirectResponse::class, $response);
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

    private function makeUser(?string $stripeCustomerId): User
    {
        $user = new User();
        $user->forceFill([
            'id' => 7,
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => 'password',
            'role_id' => 1,
            'stripe_customer_id' => $stripeCustomerId,
        ]);

        return $user;
    }
}
