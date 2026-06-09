<?php

namespace App\Services\Stripe;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Cashier\Cashier;
use Stripe\Price;
use Stripe\StripeClient;

class StripePlanCatalogService
{
    /**
     * Create or reuse the Stripe products and prices used by the plan table.
     *
     * The app stores the Stripe price id locally, so the checkout flow can use
     * a real Stripe object without needing manual dashboard setup.
     */
    public function sync(): array
    {
        $secret = Config::get('services.stripe.secret');

        if (blank($secret)) {
            throw new InvalidArgumentException('STRIPE_SECRET is missing. Set it before syncing plans.');
        }

        $plans = $this->planDefinitions();
        $currency = strtolower((string) Config::get('services.stripe.currency', 'inr'));
        $stripe = Cashier::stripe();

        $syncedPlans = [];

        foreach ($plans as $lookupKey => $plan) {
            $price = $this->findPriceByLookupKey($stripe, $lookupKey, $currency);

            if (! $this->priceMatchesPlan($price, $plan)) {
                $price = $this->createPrice(
                    $stripe,
                    $plan,
                    $lookupKey,
                    $currency,
                    $price !== null,
                    $plan['product_id'] ?? $price?->product
                );
            }

            $syncedPlans[] = [
                'name' => $plan['name'],
                'description' => $plan['description'],
                'pricing' => $plan['pricing'],
                'duration' => $plan['duration'],
                'stripe_price_id' => $price->id,
                'stripe_product_id' => $price->product,
            ];
        }

        return $syncedPlans;
    }

    /**
     * Keep all plan details in one place so the seeder and command stay tiny.
     */
    protected function planDefinitions(): array
    {
        return [
            'hobby_monthly' => [
                'name' => 'Hobby',
                'description' => 'Perfect for individuals and beginners.',
                'pricing' => 49,
                'duration' => 'monthly',
                'interval' => 'month',
                'product_id' => Config::get('services.stripe.plan_product_ids.hobby_monthly'),
            ],
            'basic_monthly' => [
                'name' => 'Basic',
                'description' => 'Best for small projects.',
                'pricing' => 149,
                'duration' => 'monthly',
                'interval' => 'month',
                'product_id' => Config::get('services.stripe.plan_product_ids.basic_monthly'),
            ],
            'pro_monthly' => [
                'name' => 'Pro',
                'description' => 'Limited access to feature.',
                'pricing' => 499,
                'duration' => 'monthly',
                'interval' => 'month',
                'product_id' => Config::get('services.stripe.plan_product_ids.pro_monthly'),
            ],
            'hobby_annual' => [
                'name' => 'Hobby',
                'description' => 'Perfect for individuals and beginners.',
                'pricing' => 599,
                'duration' => 'annual',
                'interval' => 'year',
                'product_id' => Config::get('services.stripe.plan_product_ids.hobby_annual'),
            ],
            'basic_annual' => [
                'name' => 'Basic',
                'description' => 'Best for small projects.',
                'pricing' => 1499,
                'duration' => 'annual',
                'interval' => 'year',
                'product_id' => Config::get('services.stripe.plan_product_ids.basic_annual'),
            ],
            'pro_annual' => [
                'name' => 'Pro',
                'description' => 'Unlimited access to feature.',
                'pricing' => 2999,
                'duration' => 'annual',
                'interval' => 'year',
                'product_id' => Config::get('services.stripe.plan_product_ids.pro_annual'),
            ],
        ];
    }

    /**
     * Look for an active price using the stable lookup key.
     */
    protected function findPriceByLookupKey(StripeClient $stripe, string $lookupKey, string $currency): ?Price
    {
        $prices = $stripe->prices->all([
            'lookup_keys' => [$lookupKey],
            'currency' => $currency,
            'active' => true,
            'limit' => 1,
        ]);

        return $prices->data[0] ?? null;
    }

    /**
     * Create a Stripe product and recurring price in one API call.
     */
    protected function createPrice(
        StripeClient $stripe,
        array $plan,
        string $lookupKey,
        string $currency,
        bool $transferLookupKey = false,
        ?string $productId = null
    ): Price
    {
        $payload = [
            'currency' => $currency,
            'unit_amount' => $this->toMinorUnit((int) $plan['pricing']),
            'lookup_key' => $lookupKey,
            'transfer_lookup_key' => $transferLookupKey,
            'recurring' => [
                'interval' => $plan['interval'],
            ],
            'metadata' => [
                'plan_key' => $lookupKey,
                'plan_name' => $plan['name'],
                'plan_duration' => $plan['duration'],
            ],
        ];

        if ($productId) {
            $payload['product'] = $productId;
        } else {
            $payload['product_data'] = [
                'name' => $this->productName($plan['name'], $plan['duration']),
                'metadata' => [
                    'plan_key' => $lookupKey,
                    'plan_name' => $plan['name'],
                    'plan_duration' => $plan['duration'],
                ],
            ];
        }

        return $stripe->prices->create($payload);
    }

    /**
     * Check if an existing Stripe price still matches the local plan data.
     */
    protected function priceMatchesPlan(?Price $price, array $plan): bool
    {
        if (! $price) {
            return false;
        }

        return (int) $price->unit_amount === $this->toMinorUnit((int) $plan['pricing'])
            && ($price->recurring->interval ?? null) === $plan['interval']
            && (
                blank($plan['product_id'] ?? null)
                || ($price->product ?? null) === $plan['product_id']
            );
    }

    /**
     * Stripe expects the amount in the smallest currency unit.
     */
    protected function toMinorUnit(int $amount): int
    {
        return $amount * 100;
    }

    /**
     * Give the Stripe product a readable name that matches the local plan.
     */
    protected function productName(string $name, string $duration): string
    {
        return Str::title($name.' '.$duration);
    }
}
