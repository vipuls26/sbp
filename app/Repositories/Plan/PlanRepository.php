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
        return Plan::updateOrCreate(
            ['id' => $id],
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'pricing' => $data['pricing'],
                'duration' => $data['duration'],
            ]
        );
    }

    // delete a plan record
    public function delete(int $id)
    {
        return Plan::destroy($id);
    }
}
