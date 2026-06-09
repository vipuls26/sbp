<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Models\Plan;
use App\Models\User;
use App\Interfaces\Auth\UserRepositoryInterface;
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
        private PaymentRepositoryInterface $paymentRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    // Create a Stripe Checkout session and store a pending payment row.
    public function createCheckoutSession(Plan $plan, User $user): RedirectResponse
    {
        try {
            $customerId = $this->getStripeCustomerId($user);

            $session = $this->stripeService->createCheckoutSession(
                $plan,
                $user->id,
                $customerId,
                url('/payment/success/' . $plan->id),
                route('payments.cancel', $plan),
            );
        } catch (\Throwable $e) {
            Log::error('Stripe checkout session could not be created.', [
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Payment session could not be created. Please try again.');
        }

        $this->createPendingPayment($plan, $user->id, $session['id']);

        return redirect()->away($session['url']);
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
            $this->markPaymentAsFailed($sessionId, $paymentIntentId, $customerId);

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

        $this->paymentRepository->transaction(function () use ($sessionId, $paymentIntentId, $customerId, $subscriberId, $planId, $payment) {
            $this->markPaymentAsSuccess($sessionId, $paymentIntentId, $customerId);

            $subscriberId = $subscriberId ?: (int) $payment->subscriber_id;
            $planId = $planId ?: (int) $payment->plan_id;

            $this->subscriptionService->update($subscriberId, $planId);
        });

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ]);
    }

    private function createPendingPayment(Plan $plan, int $subscriberId, string $sessionId): void
    {
        $this->paymentService->create([
            'subscriber_id' => $subscriberId,
            'plan_id' => $plan->id,
            'stripe_session_id' => $sessionId,
            'stripe_payment_intent_id' => null,
            'stripe_customer_id' => null,
            'amount' => $plan->pricing,
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    private function getStripeCustomerId(User $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        $stripeCustomerId = $this->stripeService->createCustomer($user->name, $user->email);

        $this->userRepository->updateStripeCustomerId($user->id, $stripeCustomerId);
        $user->forceFill([
            'stripe_customer_id' => $stripeCustomerId,
        ]);

        return $stripeCustomerId;
    }

    private function markPaymentAsSuccess(string $sessionId, mixed $paymentIntentId, mixed $customerId): void
    {
        $this->paymentService->updateBySessionId($sessionId, [
            'stripe_payment_intent_id' => $paymentIntentId,
            'stripe_customer_id' => $customerId,
            'status' => 'success',
            'paid_at' => now(),
        ]);
    }

    private function markPaymentAsFailed(string $sessionId, mixed $paymentIntentId, mixed $customerId): void
    {
        $this->paymentService->updateBySessionId($sessionId, [
            'stripe_payment_intent_id' => $paymentIntentId,
            'stripe_customer_id' => $customerId,
            'status' => 'failed',
        ]);
    }

}
