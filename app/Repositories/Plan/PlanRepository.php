<?php

namespace App\Repositories\Plan;

use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PlanRepository implements PlanRepositoryInterface
{
   
    // create plan in db
    public function create(array $data)
    {
        $plan = Plan::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'pricing' => $data['pricing'],
            'duration' => $data['duration'],
            'is_active' => array_key_exists('is_active', $data)
                ? $this->$data['is_active']
                : 'true',
            'admin_id' => Auth::id()
        ]);

        return $plan;
    }

    // active/deactive plan
    public function status(int $id)
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

    // update plan
    public function update(int $id, array $data)
    {

        $plan =  Plan::updateOrCreate(
            ['id' => $id],
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'pricing' => $data['pricing'],
                'duration' => $data['duration']
            ]
        );

        return $plan;
    }

    // delete plan
    public function destroy(int $id)
    {
        return Plan::destroy($id);
    }
}
