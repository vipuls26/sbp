<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Plan;
use App\Services\Payment\PaymentService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private SubscriptionService $subscriptionService
    ) {}

    public function create(Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        return view('payment.page', compact('plan'));
    }

    public function store(PaymentRequest $request, Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        DB::transaction(function () use ($plan) {
            // store payment first
            $this->paymentService->create([
                'subscriber_id' => Auth::id(),
                'plan_id' => $plan->id,
                'razor_order_id' => 'TEST-ORDER-' . now()->timestamp,
                'razor_payment_id' => 'TEST-PAY-' . now()->timestamp,
                'razor_signature' => 'TEST-SIGN-' . now()->timestamp,
                'amount' => $plan->pricing,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            // then activate or update the subscription
            $this->subscriptionService->update(Auth::id(), $plan->id);
        });

        return redirect()->route('user.plans')->with('success', 'Payment completed and subscription activated successfully.');
    }
}
