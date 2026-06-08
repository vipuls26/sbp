<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function test()
    {
        Stripe::setApiKey(config(
            'services.stripe.secret'
        ));

        $plans = Plan::get();
        dd($plans);
    }
}
