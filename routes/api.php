<?php

use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// Cashier's built-in webhook handler is at /stripe/webhook.
// This custom controller extends Cashier's to handle post-payment events.
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handleWebhook']);
