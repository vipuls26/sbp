<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\Payment\PaymentService;
use App\Services\Razorpay\RazorpayApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mockery;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    public function test_create_shows_payment_page_and_saves_pending_payment(): void
    {
        Config::set('services.razorpay.key', 'rzp_test_key');

        Auth::shouldReceive('id')
            ->twice()
            ->andReturn(7);

        $plan = $this->makePlan();
        $order = ['id' => 'order_test_123'];

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function (array $data) use ($plan) {
                return $data['subscriber_id'] === 7
                    && $data['plan_id'] === 1
                    && $data['razor_order_id'] === 'order_test_123'
                    && $data['amount'] === $plan->pricing
                    && $data['status'] === 'pending';
            }))
            ->andReturn(new Payment());

        $razorpayApiService = Mockery::mock(RazorpayApiService::class);
        $razorpayApiService->shouldReceive('createOrder')
            ->once()
            ->with(Mockery::on(function (array $data) use ($plan) {
                return str_starts_with($data['receipt'], 'plan_1_7_')
                    && $data['amount'] === (int) round($plan->pricing * 100)
                    && $data['currency'] === 'INR';
            }))
            ->andReturn($order);

        $controller = new PaymentController(
            $paymentService,
            $razorpayApiService
        );

        $view = $controller->create($plan);

        $this->assertInstanceOf(View::class, $view);
        $this->assertSame('payment.payment', $view->name());
        $this->assertSame('order_test_123', $view->getData()['orderId']);
        $this->assertSame((int) round($plan->pricing * 100), $view->getData()['amount']);
        $this->assertSame('INR', $view->getData()['currency']);
        $this->assertSame('rzp_test_key', $view->getData()['razorpayKey']);
    }

    public function test_store_verifies_signature_and_keeps_payment_pending(): void
    {
        $plan = $this->makePlan();
        $orderId = 'order_test_456';
        $paymentId = 'pay_test_456';
        $signature = hash_hmac('sha256', $orderId . '|' . $paymentId, 'rzp_test_secret');

        $validatedRequest = Mockery::mock(PaymentRequest::class);
        $validatedRequest->shouldReceive('validated')
            ->once()
            ->andReturn([
                'razorpay_payment_id' => $paymentId,
                'razorpay_order_id' => $orderId,
                'razorpay_signature' => $signature,
            ]);

        $pendingPayment = new Payment();
        $pendingPayment->forceFill([
            'status' => 'pending',
            'razor_signature' => null,
        ]);

        $paymentService = Mockery::mock(PaymentService::class);
        $paymentService->shouldReceive('findByOrderId')
            ->once()
            ->with($orderId)
            ->andReturn($pendingPayment);
        $paymentService->shouldReceive('updateByOrderId')
            ->once()
            ->with($orderId, [
                'razor_signature' => $signature,
            ]);
        $razorpayApiService = Mockery::mock(RazorpayApiService::class);
        $razorpayApiService->shouldReceive('verifyPaymentSignature')
            ->once()
            ->with([
                'razorpay_payment_id' => $paymentId,
                'razorpay_order_id' => $orderId,
                'razorpay_signature' => $signature,
            ]);

        $controller = new PaymentController(
            $paymentService,
            $razorpayApiService
        );

        $response = $controller->store($validatedRequest, $plan);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(route('user.plans'), $response->getTargetUrl());
        $this->assertSame('Payment received successfully.', $response->getSession()->get('success'));
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
