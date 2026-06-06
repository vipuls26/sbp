<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Plan;
use App\Services\Payment\PaymentService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private SubscriptionService $subscriptionService
    ) {}

    public function create(Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $order = $api->order->create([
                'receipt' => 'plan_' . $plan->id . '_' . Auth::id() . '_' . now()->timestamp,
                'amount' => (int) round($plan->pricing * 100),
                'currency' => 'INR',
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'Payment order could not be created. Please try again.');
        }

        // Save the payment attempt before the checkout opens.
        $payment = $this->paymentService->create([
            'subscriber_id' => Auth::id(),
            'plan_id' => $plan->id,
            'razor_order_id' => $order['id'],
            'razor_payment_id' => null,
            'razor_signature' => null,
            'amount' => $plan->pricing,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return view('payment.page', [
            'plan' => $plan,
            'razorpayKey' => config('services.razorpay.key'),
            'orderId' => $order['id'],
            'paymentId' => $payment->id,
            'amount' => (int) round($plan->pricing * 100),
            'currency' => 'INR',
        ]);
    }

    public function store(PaymentRequest $request, Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $validated = $request->validated();
        $payment = $this->paymentService->findByOrderId($validated['razorpay_order_id']);

        if (! $payment) {
            return back()->with('error', 'Pending payment record not found. Please try again.');
        }

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);
        } catch (SignatureVerificationError $e) {
            $this->paymentService->updateByOrderId($validated['razorpay_order_id'], [
                'razor_payment_id' => $validated['razorpay_payment_id'],
                'razor_signature' => $validated['razorpay_signature'],
                'status' => 'failed',
            ]);

            return back()->with('error', 'Payment verification failed. Please try again.');
        }

        DB::transaction(function () use ($plan, $validated) {
            $razorpaySignature = $validated['razorpay_signature'];

            $this->paymentService->updateByOrderId($validated['razorpay_order_id'], [
                'razor_payment_id' => $validated['razorpay_payment_id'],
                'razor_signature' => $razorpaySignature,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            $this->subscriptionService->update(Auth::id(), $plan->id);
        });

        return redirect()->route('user.plans')->with('success', 'Payment completed and subscription activated successfully.');
    }
}
