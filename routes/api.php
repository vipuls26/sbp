<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/webhooks/razorpay', function () {
    return response()->json(['message' => 'Webhook endpoint is ready']);
});

Route::post('/webhooks/razorpay', [WebhookController::class, 'handle']);
