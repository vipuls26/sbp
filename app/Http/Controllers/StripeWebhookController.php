<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Fired when a Stripe invoice is paid (renewal, upgrade, etc.).
     * Creates or updates the local payment record.
     */
    public function handleInvoicePaymentSucceeded(array $payload): void
    {
        $invoice = $payload['data']['object'];

        $subscriptionId = $invoice['subscription'] ?? null;
        $customerId = $invoice['customer'] ?? null;

        if (! $subscriptionId || ! $customerId) {
            return;
        }

        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            Log::warning('Webhook: no user found for Stripe customer.', ['customer' => $customerId]);
            return;
        }

        $subscription = $user->subscriptions()->where('stripe_id', $subscriptionId)->first();

        Payment::updateOrCreate(
            ['stripe_invoice_id' => $invoice['id']],
            [
                'user_id'                    => $user->id,
                'subscriber_id'              => $user->id,
                'plan_id'                    => $subscription?->plan_id,
                'stripe_subscription_id'     => $subscriptionId,
                'stripe_invoice_id'          => $invoice['id'],
                'stripe_customer_id'         => $customerId,
                'stripe_payment_intent_id'   => $invoice['payment_intent'] ?? null,
                'currency'                   => strtoupper($invoice['currency'] ?? 'INR'),
                'amount'                     => ($invoice['amount_paid'] ?? 0) / 100,
                'status'                     => 'success',
                'paid_at'                    => now(),
            ]
        );

        Log::info('Webhook: invoice.payment_succeeded processed.', [
            'invoice_id' => $invoice['id'],
            'user_id'    => $user->id,
        ]);
    }

    /**
     * Fired when a Stripe subscription is deleted (immediate cancel or end of period).
     * Marks the local subscription as cancelled.
     */
    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        $stripeSubscription = $payload['data']['object'];
        $customerId = $stripeSubscription['customer'] ?? null;

        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            return;
        }

        $subscription = $user->subscriptions()->where('stripe_id', $stripeSubscription['id'])->first();

        if (! $subscription) {
            return;
        }

        $periodEnd = data_get(
            $stripeSubscription,
            'items.data.0.current_period_end'
        );

        $endDate = $periodEnd
            ? Carbon::createFromTimestamp($periodEnd)->toDateTimeString()
            : now()->toDateTimeString();

        $subscription->update([
            'status' => 'cancelled',
            'stripe_status' => 'canceled',
            'cancel_at_period_end' => false,
            'end_date' => $endDate,
        ]);

        Log::info('Webhook: customer.subscription.deleted processed.', [
            'stripe_subscription_id' => $stripeSubscription['id'],
            'user_id'                => $user->id,
        ]);
    }

    /**
     * Fired when a Stripe subscription is updated (plan swap, renewal date change, etc.).
     * Syncs the local subscription status and end date.
     */
    public function handleCustomerSubscriptionUpdated(array $payload): void
    {
        $stripeSubscription = $payload['data']['object'];
        $customerId = $stripeSubscription['customer'] ?? null;

        $user = User::where('stripe_id', $customerId)->first();

        if (! $user) {
            return;
        }

        $subscription = $user->subscriptions()->where('stripe_id', $stripeSubscription['id'])->first();

        if (! $subscription) {
            return;
        }

        $periodEnd = data_get(
            $stripeSubscription,
            'items.data.0.current_period_end'
        );

        $endDate = $periodEnd
            ? Carbon::createFromTimestamp($periodEnd)->toDateTimeString()
            : null;

        $subscription->update([
            'stripe_status' => $stripeSubscription['status'] ?? null,
            'cancel_at_period_end' => $stripeSubscription['cancel_at_period_end'] ?? false,
            'end_date' => $endDate,
        ]); 

        Log::info('Webhook: customer.subscription.updated processed.', [
            'stripe_subscription_id' => $stripeSubscription['id'],
            'user_id'                => $user->id,
        ]);
    }
}
