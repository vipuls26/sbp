<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentService;
use App\Models\Plan;
use App\Models\Payment;
use App\Services\Subscription\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private PaymentService $paymentService
    ) {}

    // show pricing page to user
    public function index()
    {
        return redirect()->route('user.plans');
    }

    // start a subscription by moving the user to payment
    public function store(Request $request, Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        if (blank($plan->stripe_price_id)) {
            Log::warning('Stripe price id is missing for this plan.', [
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
            ]);

            return redirect()->route('user.plans')->with(
                'error',
                'Stripe price id is missing for this plan.'
            );
        }

        try {
            // Stripe Checkout creates the actual payment page for us.
            return $request->user()
                ->newSubscription('default', $plan->stripe_price_id)
                ->checkout([
                    'success_url' => route('subscriptions.success', $plan) . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('subscriptions.cancel', $plan),
                    'metadata' => [
                        'plan_id' => $plan->id,
                        'user_id' => Auth::id(),
                    ],
                ]);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout session could not be created.', [
                'plan_id' => $plan->id,
                'user_id' => Auth::id(),
                'plan_stripe_price_id' => $plan->stripe_price_id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('user.plans')->with('error', 'Payment page could not be created.');
        }
    }

    // handle successful Stripe checkout redirect
    public function success(Request $request, Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        $sessionId = $request->string('session_id')->toString();

        if ($sessionId === '') {
            return redirect()->route('user.plans')->with('error', 'Stripe session id is missing.');
        }

        try {
            $session = $request->user()->stripe()->checkout->sessions->retrieve($sessionId);
        } catch (\Throwable $e) {
            Log::warning('Stripe checkout session could not be loaded.', [
                'session_id' => $sessionId,
            ]);

            return redirect()->route('user.plans')->with('error', 'Payment confirmation could not be loaded.');
        }

        if (($session->payment_status ?? null) !== 'paid') {
            return redirect()->route('user.plans')->with('error', 'Payment is not complete yet.');
        }

        $stripeSubscription = null;
        $stripeSubscriptionId = $session->subscription ?? null;
        $stripePaymentIntentId = $session->payment_intent ?? null;
        $stripeCustomerId = $session->customer ?? null;
        $amount = $session->amount_total ? ($session->amount_total / 100) : $plan->pricing;
        $currency = strtoupper($session->currency ?? 'INR');

        if ($stripeSubscriptionId) {
            try {
                $stripeSubscription = $request->user()->stripe()->subscriptions->retrieve($stripeSubscriptionId);
            } catch (\Throwable $e) {
                Log::warning('Stripe subscription could not be loaded after checkout.', [
                    'stripe_subscription_id' => $stripeSubscriptionId,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $endDate = $this->getStripePeriodEnd($stripeSubscription)
            ?? ($plan->duration === 'annual'
                ? now()->addYear()->toDateTimeString()
                : now()->addDays(30)->toDateTimeString());

        $this->paymentService->updateOrCreateByStripeSessionId($sessionId, [
            'user_id' => Auth::id(),
            'subscriber_id' => Auth::id(),
            'plan_id' => $plan->id,
            'stripe_checkout_session_id' => $sessionId,
            'stripe_payment_intent_id' => $stripePaymentIntentId,
            'stripe_subscription_id' => $stripeSubscriptionId,
            'stripe_invoice_id' => $session->invoice ?? null,
            'stripe_customer_id' => $stripeCustomerId,
            'currency' => $currency,
            'amount' => $amount,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        $this->subscriptionService->update(Auth::id(), $plan->id, [
            'stripe_id' => $stripeSubscriptionId,
            'stripe_status' => 'active',
            'start_date' => now()->toDateTimeString(),
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        return redirect()
            ->route('user.plans')
            ->with('success', 'Subscription activated successfully.');
    }

    // cancel return route after Stripe checkout
    public function cancel(Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        return redirect()
            ->route('user.plans')
            ->with('error', 'Payment was canceled.');
    }

    // update subscription
    public function update(Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        $subscription = Auth::user()->subscription('default');

        if (! $subscription) {
            return redirect()->route('user.plans')->with('error', 'No active subscription found.');
        }

        $currentPlan = $subscription->plan;
        $isUpgrade = ! $currentPlan || $plan->pricing > $currentPlan->pricing;

        try {
            if ($isUpgrade) {
                $subscription->swapAndInvoice($plan->stripe_price_id);
                $subscription->refresh();

                $stripeSubscription = $subscription->asStripeSubscription();
                $invoice = $subscription->latestInvoice();

                if ($invoice && ! $invoice->isPaid()) {
                    Log::warning('Stripe upgrade invoice is not paid yet.', [
                        'plan_id' => $plan->id,
                        'user_id' => Auth::id(),
                        'invoice_id' => $invoice->asStripeInvoice()->id ?? null,
                        'invoice_status' => $invoice->asStripeInvoice()->status ?? null,
                    ]);

                    return redirect()->route('user.plans')->with(
                        'error',
                        'Payment was not completed for the upgrade.'
                    );
                }

                $stripePeriodEnd = $this->getStripePeriodEnd($stripeSubscription);
                $invoiceAmount = $invoice ? $invoice->rawAmountPaid() : null;
                $invoiceCurrency = strtoupper($invoice?->asStripeInvoice()->currency ?? 'INR');
                $invoiceId = $invoice?->asStripeInvoice()->id ?? null;

                $this->paymentService->create([
                    'user_id' => Auth::id(),
                    'subscriber_id' => Auth::id(),
                    'plan_id' => $plan->id,
                    'stripe_subscription_id' => $subscription->stripe_id,
                    'stripe_invoice_id' => $invoiceId,
                    'currency' => $invoiceCurrency,
                    'amount' => $invoiceAmount ? ($invoiceAmount / 100) : $plan->pricing,
                    'status' => 'success',
                    'paid_at' => now(),
                ]);

                $this->subscriptionService->update(Auth::id(), $plan->id, [
                    'stripe_id' => $subscription->stripe_id,
                    'stripe_status' => $subscription->stripe_status,
                    'start_date' => now()->toDateTimeString(),
                    'end_date' => $stripePeriodEnd,
                    'status' => 'active',
                ]);

                return redirect()->route('user.plans')->with('success', 'Subscription upgraded successfully.');
            }

            // Downgrades move to the new price without prorating the charges.
            $subscription->noProrate()->swap($plan->stripe_price_id);
            $subscription->refresh();

            $this->subscriptionService->update(Auth::id(), $plan->id, [
                'stripe_id' => $subscription->stripe_id,
                'stripe_status' => $subscription->stripe_status,
                'start_date' => now()->toDateTimeString(),
                'end_date' => $this->getStripePeriodEnd($subscription->asStripeSubscription()),
                'status' => 'active',
            ]);

            return redirect()->route('user.plans')->with('success', 'Subscription downgraded successfully.');
        } catch (\Throwable $e) {
            Log::warning('Stripe subscription swap failed.', [
                'plan_id' => $plan->id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('user.plans')->with('error', 'Subscription update failed.');
        }
    }

    // cancel subscription at the end of the billing cycle
    public function destroy()
    {
        $subscription = Auth::user()->subscription('default');

        if (! $subscription) {
            return redirect()->route('user.plans')->with('error', 'No active subscription found.');
        }

        try {
            $subscription->cancel();
            $subscription->refresh();
        } catch (\Throwable $e) {
            Log::warning('Stripe subscription cancel failed.', [
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('user.plans')->with('error', 'Subscription cancel failed.');
        }

        $this->subscriptionService->cancel(Auth::id(), [
            'status' => 'cancelled',
            'end_date' => $subscription->ends_at ?? $this->getStripePeriodEnd($subscription->asStripeSubscription()),
            'stripe_status' => $subscription->stripe_status,
            'stripe_id' => $subscription->stripe_id,
        ]);

        return redirect()->route('user.plans')->with('success', 'Subscription will end at the end of the billing period.');
    }

    // download invoice PDF for a payment
    public function downloadInvoice(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        if (blank($payment->stripe_invoice_id)) {
            return redirect()->route('user.plans')->with('error', 'Invoice is not available for this payment.');
        }

        try {
            $filename = 'invoice-'.$payment->id.'.pdf';

            return Auth::user()->downloadInvoice($payment->stripe_invoice_id, [], $filename);
        } catch (\Throwable $e) {
            Log::warning('Stripe invoice download failed.', [
                'payment_id' => $payment->id,
                'user_id' => Auth::id(),
                'invoice_id' => $payment->stripe_invoice_id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('user.plans')->with('error', 'Invoice could not be downloaded.');
        }
    }

    /**
     * Convert Stripe's timestamp into a normal datetime string for the local DB.
     */
    protected function getStripePeriodEnd($stripeSubscription): ?string
    {
        $periodEnd = $stripeSubscription->current_period_end ?? null;

        if (! $periodEnd) {
            return null;
        }

        return Carbon::createFromTimestamp($periodEnd)->toDateTimeString();
    }
}
