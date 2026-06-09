<?php

namespace App\Repositories\Plan;

use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PlanRepository implements PlanRepositoryInterface
{

    // create a plan record
    public function create(array $data)
    {
        return Plan::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'pricing' => $data['pricing'],
            'duration' => $data['duration'],
            'stripe_price_id' => $data['stripe_price_id'],
            'stripe_product_id' => $data['stripe_product_id'] ?? null,
            'admin_id' => Auth::id(),
        ]);
    }

    // toggle active/deactive plan status
    public function toggleStatus(int $id)
    {
        $plan = Plan::withCount('subscriptions')->findOrFail($id);

        if ($plan->is_active === 'true' && $plan->subscriptions_count > 0) {
            return [
                'success' => false,
                'message' => 'This plan have active users',
            ];
        }

        $plan->is_active = $plan->is_active === 'true' ? 'false' : 'true';
        $plan->save();

        return [
            'success' => true,
            'message' => $plan->is_active === 'true'
                ? 'Plan activated successfully'
                : 'Plan deactivated successfully',
        ];
    }

    // update a plan record
    public function update(int $id, array $data)
    {
        $plan = Plan::findOrFail($id);

        $payload = [
            'name' => $data['name'],
            'description' => $data['description'],
            'pricing' => $data['pricing'],
            'duration' => $data['duration'],
            'stripe_price_id' => $data['stripe_price_id'],
        ];

        // Keep the existing Stripe product id unless the caller sends a new one.
        if (array_key_exists('stripe_product_id', $data)) {
            $payload['stripe_product_id'] = $data['stripe_product_id'];
        }

        $plan->update($payload);

        return $plan;
    }

    // delete a plan record
    public function delete(int $id)
    {
        return Plan::destroy($id);
    }
}
