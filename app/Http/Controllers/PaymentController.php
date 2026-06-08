<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Plan;
use App\Services\Payment\PaymentService;
use App\Services\Razorpay\RazorpayApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private RazorpayApiService $razorpayApiService
    ) {}

    //   create subscription with razorpay
    public function create(Plan $plan)
    {
        // check if plan active
        abort_if($plan->is_active !== 'true', 404);

        try {
            $order = $this->razorpayApiService->createOrder([
                'receipt' => 'plan_' . $plan->id . '_' . Auth::id() . '_' . now()->timestamp,
                'amount' => (int) round($plan->pricing * 100),
                'currency' => 'INR'
            ]);

        } catch (\Throwable $e) {
            Log::error('Payment order could not be created.', [
                'plan_id' => $plan->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Payment order could not be created. Please try again.');
        }


        // Save a pending payment record before opening Razorpay checkout.
        $this->paymentService->create([
            'subscriber_id' => Auth::id(),
            'plan_id' => $plan->id,
            'razor_order_id' => $order['id'],
            'razor_payment_id' => null,
            'razor_signature' => null,
            'amount' => $plan->pricing,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        // return on balde file with payment detail to pay
        return view('payment.payment', [
            'plan' => $plan,
            'razorpayKey' => config('services.razorpay.key'),
            'orderId' => $order['id'],
            'amount' => (int) round($plan->pricing * 100),
            'currency' => 'INR',
        ]);
    }

    // if payment made then this save data in db
    public function store(PaymentRequest $request, Plan $plan)
    {
        Log::info('Browser callback executed');

        abort_if($plan->is_active !== 'true', 404);

        $validated = $request->validated();

        $payment = $this->paymentService->findByOrderId(
            $validated['razorpay_order_id']
        );

        if (! $payment) {
            return redirect()
                ->route('user.plans')
                ->with('error', 'Payment record not found.');
        }

        // Payment already processed by webhook
        if ($payment->status === 'success') {
            // Store signature if webhook processed first
            if (empty($payment->razor_signature)) {
                $this->paymentService->updateByOrderId(
                    $validated['razorpay_order_id'],
                    [
                        'razor_signature' => $validated['razorpay_signature'],
                    ]
                );
            }

            return redirect()
                ->route('user.plans')
                ->with('success', 'Plan subscribe successfully.');
        }

        try {
            $this->razorpayApiService->verifyPaymentSignature($validated);
        } catch (\Throwable $e) {
            Log::warning('Browser callback signature verification failed.', [
                'order_id' => $validated['razorpay_order_id'],
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('user.plans')
                ->with('error', 'Payment verification failed.');
        }

        // Payment status and subscription are handled by webhook.
        $this->paymentService->updateByOrderId(
            $validated['razorpay_order_id'],
            [
                'razor_signature' => $validated['razorpay_signature'],
            ]
        );

        return redirect()
            ->route('user.plans')
            ->with('success', 'Payment received successfully.');
    }
}
