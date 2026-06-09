<?php

namespace App\Services\Stripe;

use App\Models\Plan;
use Stripe\Customer as StripeCustomer;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    public function createCustomer(string $name, string $email): string
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $customer = StripeCustomer::create([
            'name' => $name,
            'email' => $email,
        ]);

        return $customer->id;
    }

    public function createCheckoutSession(
        Plan $plan,
        int $subscriberId,
        string $customerId,
        string $successUrl,
        string $cancelUrl,
    ): array {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeCheckoutSession::create([
            'mode' => 'payment',
            'customer' => $customerId,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string) $subscriberId,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => $plan->name,
                            'description' => $plan->description,
                        ],
                        'unit_amount' => (int) round($plan->pricing * 100),
                    ],
                    'quantity' => 1,
                ],
            ],
            'metadata' => [
                'plan_id' => (string) $plan->id,
                'subscriber_id' => (string) $subscriberId,
            ],
        ])->toArray();

        return $session;
    }

    public function retrieveCheckoutSession(string $sessionId): \Stripe\Checkout\Session
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return StripeCheckoutSession::retrieve($sessionId);
    }

    /**
     * Verify the Stripe webhook signature and return the event.
     *
     * @throws SignatureVerificationException
     */
    public function constructWebhookEvent(string $payload, string $signature): \Stripe\Event
    {
        return Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );
    }
}
