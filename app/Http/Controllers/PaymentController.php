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
        // check again if plan is active
        abort_if($plan->is_active !== 'true', 404);

        // initialize Razorpay API again
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        // get data validated
        $validated = $request->validated();

        // find save record with pending status
        $payment = $this->paymentService->findByOrderId($validated['razorpay_order_id']);


        if (! $payment) {
            return back()->with('error', 'Pending payment record not found. Please try again.');
        }

        // verify the payment signature.
        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);
        } catch (SignatureVerificationError $e) {
            // change status as failed if signature not match
            $this->paymentService->updateByOrderId($validated['razorpay_order_id'], [
                'razor_payment_id' => $validated['razorpay_payment_id'],
                'razor_signature' => $validated['razorpay_signature'],
                'status' => 'failed',
            ]);

            return back()->with('error', 'Payment verification failed. Please try again.');
        }

        DB::transaction(function () use ($plan, $validated) {
            $razorpaySignature = $validated['razorpay_signature'];

            // update record if payment is complete with paid at timestamp
            $this->paymentService->updateByOrderId($validated['razorpay_order_id'], [
                'razor_payment_id' => $validated['razorpay_payment_id'],
                'razor_signature' => $razorpaySignature,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            // update subscription plan
            $this->subscriptionService->update(Auth::id(), $plan->id);
        });

        // redirect to dashboard
        return redirect()->route('user.plans')->with('success', 'Payment completed and subscription activated successfully.');
    }
}
