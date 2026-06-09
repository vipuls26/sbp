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

        $user = Auth::user();
        abort_unless($user, 403);

        return $this->paymentFlowService->createCheckoutSession(
            $plan,
            $user,
        );
    }

    // Stripe redirects back here after checkout, then we send the user to the plans page.
    public function success(Plan $plan)
    {
        return redirect()
            ->route('user.plans')
            ->with('success', 'Payment completed successfully.');
    }

    // Stripe cancel page helper.
    public function cancel()
    {
        return redirect()
            ->route('user.plans')
            ->with('error', 'Payment was canceled.');
    }
}
