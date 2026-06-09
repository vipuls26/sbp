<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\Payment\PaymentFlowService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentFlowService $paymentFlowService
    ) {}

    // Create a Stripe Checkout session and send the user to Stripe.
    public function create(Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        return $this->paymentFlowService->createCheckoutSession(
            $plan,
            Auth::id(),
            Auth::user()->email,
        );
    }

    // Stripe redirects back here after checkout.
    public function success(Plan $plan, string $sessionId)
    {
        return $this->paymentFlowService->handleSuccess($plan, $sessionId);
    }

    // Stripe cancel page helper.
    public function cancel()
    {
        return redirect()
            ->route('user.plans')
            ->with('error', 'Payment was canceled.');
    }
}
