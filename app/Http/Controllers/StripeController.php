<?php

namespace App\Http\Controllers;

use Stripe\Balance;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function test()
    {
        Stripe::setApiKey(config(
            'services.stripe.secret'
        ));
        return Balance::retrieve();
    }

    public function checkout()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => 'Pro Plan',
                    ],
                    'unit_amount' => 69900,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.success')
        ]);

        return redirect($session->url);
    }
}
