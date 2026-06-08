<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    // show pricing page to user
    public function index()
    {
        return redirect()->route('user.plans');
    }

    // start a subscription by moving the user to payment
    public function store(Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        return redirect()
            ->route('payments.create', $plan)
            ->with('success', 'Please complete the payment to activate your plan.');
    }

    // update subscription
    public function update(Plan $plan)
    {
        abort_if($plan->is_active !== 'true', 404);

        $this->subscriptionService->update(Auth::id(), $plan->id);
        return redirect()->route('user.plans')->with('success', 'Subscription Updated Successfully');
    }

    // cancel subscription
    public function destroy()
    {
        $this->subscriptionService->cancel(Auth::user()->id);
        return redirect()->route('user.plans')->with('success', 'Subscription Canceled Successfully');
    }
}
