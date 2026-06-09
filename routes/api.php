<?php

use App\Http\Controllers\StripewebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/webhooks/stripe', function () {
    return response()->json(['message' => 'Stripe webhook endpoint is ready']);
});

// Stripe sends webhook events here.
Route::post('/webhooks/stripe', [StripewebhookController::class, 'handle']);

