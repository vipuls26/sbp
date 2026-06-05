<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    // add subscription
    public function storeSubscription(Plan $plan)
    {
        return redirect()
            ->route('payment.page', $plan)
            ->with('success', 'Please complete the payment to activate your plan.');
    }

    // update subscription
    public function update(int $id)
    {
        $this->subscriptionService->update(Auth::user()->id, $id);
        return redirect()->route('user.plans')->with('success', 'Subscription Updated Successfully');
    }

    // cancel subscription
    public function cancel()
    {
        $this->subscriptionService->cancel(Auth::user()->id);
        return redirect()->route('user.plans')->with('success', 'Subscription Canceled Successfully');
    }
}
