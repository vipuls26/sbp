<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use App\Models\Plan;
use App\Services\Payment\PaymentFlowService;
use Mockery;
use Tests\TestCase;

class PaymentSuccessPageTest extends TestCase
{
    public function test_success_action_redirects_user_to_plans_page(): void
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

        $controller = new PaymentController(
            Mockery::mock(PaymentFlowService::class)
        );

        $response = $controller->success($plan);

        $this->assertSame(route('user.plans'), $response->getTargetUrl());
        $this->assertSame('Payment completed successfully.', $response->getSession()->get('success'));
    }
}
