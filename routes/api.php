<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/webhooks/razorpay', function () {
    return response()->json(['message' => 'Razorpay webhook endpoint is ready']);
});

// Razorpay sends webhook events here.
Route::post('/webhooks/razorpay', [WebhookController::class, 'handle']);
