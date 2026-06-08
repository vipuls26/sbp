<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Plan;
use App\Services\Payment\PaymentService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private SubscriptionService $subscriptionService
    ) {}

    //   create subscription with razorpay
    public function create(Plan $plan)
    {
        // check if plan active
        abort_if($plan->is_active !== 'true', 404);

        try {
            // initialize the Razorpay API using our keys from the .env file.
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            // create new order
            $order = $api->order->create([
                // generate a unique receipt ID for this payment
                'receipt' => 'plan_' . $plan->id . '_' . Auth::id() . '_' . now()->timestamp,
                // except amount in paisa
                'amount' => (int) round($plan->pricing * 100),
                'currency' => 'INR', // using indian currency
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'Payment order could not be created. Please try again.');
        }


        // save payment record in db
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

        // return on balde file with payment detail to pay
        return view('payment.payment', [
            'plan' => $plan,
            'razorpayKey' => config('services.razorpay.key'),
            'orderId' => $order['id'],
            'paymentId' => $payment->id,
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
                ->with('success', 'Payment already processed successfully.');
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);
        } catch (SignatureVerificationError $e) {
            Log::warning('Browser callback signature verification failed.', [
                'order_id' => $validated['razorpay_order_id'],
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
