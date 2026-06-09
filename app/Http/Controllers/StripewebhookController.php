<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Payment\PaymentFlowService;

class StripewebhookController extends Controller
{
    public function __construct(
        private PaymentFlowService $paymentFlowService
    ) {}

    public function handle(Request $request)
    {
        return $this->paymentFlowService->handleWebhook($request);
    }
}
