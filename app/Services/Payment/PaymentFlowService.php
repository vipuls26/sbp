<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Plan;
use App\Services\Stripe\StripeService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentFlowService
{
    public function __construct(
        private PaymentService $paymentService,
        private SubscriptionService $subscriptionService,
        private StripeService $stripeService,
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    // Create a Stripe Checkout session and store a pending payment row.
    public function createCheckoutSession(Plan $plan, int $subscriberId, string $email): RedirectResponse
    {
        try {
            $session = $this->stripeService->createCheckoutSession(
                $plan,
                $subscriberId,
                url('/payment/success/' . $plan->id . '/{CHECKOUT_SESSION_ID}'),
                route('payments.cancel', $plan),
                $email
            );
        } catch (\Throwable $e) {
            Log::error('Stripe checkout session could not be created.', [
                'plan_id' => $plan->id,
                'user_id' => $subscriberId,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Payment session could not be created. Please try again.');
        }

        $this->paymentService->create([
            'subscriber_id' => $subscriberId,
            'plan_id' => $plan->id,
            'stripe_session_id' => $session['id'],
            'stripe_payment_intent_id' => null,
            'stripe_customer_id' => null,
            'amount' => $plan->pricing,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return redirect()->away($session['url']);
    }

    // Handle the browser callback after Stripe Checkout.
    public function handleSuccess(Plan $plan, string $sessionId): RedirectResponse
    {
        abort_if($plan->is_active !== 'true', 404);

        if ($sessionId === '') {
            return $this->redirectToPlans('error', 'Payment session was not found.');
        }

        try {
            $session = $this->stripeService->retrieveCheckoutSession($sessionId);
        } catch (\Throwable $e) {
            Log::warning('Stripe checkout session could not be verified.', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return $this->redirectToPlans('error', 'Payment verification failed.');
        }

        $payment = $this->paymentService->findBySessionId($sessionId);

        if (! $payment) {
            Log::warning('Stripe payment record not found on success callback.', [
                'plan_id' => $plan->id,
                'session_id' => $sessionId,
            ]);

            return $this->redirectToPlans('error', 'Payment record not found.');
        }

        if ((int) $payment->plan_id !== (int) $plan->id) {
            Log::warning('Stripe payment record does not match the selected plan.', [
                'plan_id' => $plan->id,
                'payment_plan_id' => $payment->plan_id,
                'session_id' => $sessionId,
            ]);

            return $this->redirectToPlans('error', 'Payment record does not match the selected plan.');
        }

        // The webhook may already have completed the payment.
        if ($payment->status === 'success') {
            return $this->redirectToPlans('success', 'Plan subscribe successfully.');
        }

        if (($session->payment_status ?? null) !== 'paid') {
            Log::warning('Stripe success callback received but payment is not paid yet.', [
                'plan_id' => $plan->id,
                'session_id' => $sessionId,
                'payment_status' => $session->payment_status ?? null,
            ]);

            return $this->redirectToPlans('error', 'Payment is not completed yet.');
        }

        $this->paymentService->updateBySessionId(
            $sessionId,
            [
                'stripe_payment_intent_id' => $session->payment_intent,
                'stripe_customer_id' => $session->customer,
                'status' => 'success',
                'paid_at' => now(),
            ]
        );

        return $this->redirectToPlans('success', 'Payment completed successfully.');
    }

    // Handle Stripe webhook events and activate the subscription.
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $receivedSignature = (string) $request->header('Stripe-Signature');

        if (! config('services.stripe.webhook_secret')) {
            Log::warning('Stripe webhook secret is missing.');

            return response()->json([
                'success' => false,
                'message' => 'Webhook secret is not configured.',
            ], 500);
        }

        try {
            $event = $this->stripeService->constructWebhookEvent(
                $payload,
                $receivedSignature
            );
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature mismatch.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature.',
            ], 400);
        }

        $type = $event->type ?? null;
        $session = $event->data->object ?? null;

        if (! $session) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payload.',
            ], 400);
        }

        $sessionId = $session->id ?? null;
        $paymentIntentId = $session->payment_intent ?? null;
        $customerId = $session->customer ?? null;
        $paymentStatus = $session->payment_status ?? null;
        $metadata = $session->metadata ?? null;
        $subscriberId = (int) ($metadata->subscriber_id ?? $session->client_reference_id ?? 0);
        $planId = (int) ($metadata->plan_id ?? 0);

        if (! $sessionId) {
            Log::warning('Stripe webhook session id is missing.', [
                'event' => $type,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event ignored.',
            ]);
        }

        $payment = $this->paymentService->findBySessionId($sessionId);

        if (! $payment) {
            Log::warning('Stripe webhook payment record was not found.', [
                'event' => $type,
                'session_id' => $sessionId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment record not found. Event ignored.',
            ]);
        }

        if ($type === 'checkout.session.expired' || $paymentStatus === 'unpaid') {
            $this->paymentService->updateBySessionId($sessionId, [
                'status' => 'failed',
                'stripe_payment_intent_id' => $paymentIntentId,
                'stripe_customer_id' => $customerId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment marked as failed.',
            ]);
        }

        if ($paymentStatus !== 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Event ignored.',
            ]);
        }

        if ($payment->status === 'success') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already processed successfully.',
            ]);
        }

        $this->paymentRepository->transaction(function () use ($sessionId, $paymentIntentId, $customerId, $subscriberId, $planId) {
            $this->paymentService->updateBySessionId($sessionId, [
                'stripe_payment_intent_id' => $paymentIntentId,
                'stripe_customer_id' => $customerId,
                'status' => 'success',
                'paid_at' => now(),
            ]);

            $payment = $this->paymentService->findBySessionId($sessionId);

            if ($payment) {
                $subscriberId = $subscriberId ?: (int) $payment->subscriber_id;
                $planId = $planId ?: (int) $payment->plan_id;

                $this->subscriptionService->update(
                    $subscriberId,
                    $planId
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ]);
    }

    private function redirectToPlans(string $type, string $message): RedirectResponse
    {
        return redirect()
            ->route('user.plans')
            ->with($type, $message);
    }
}
