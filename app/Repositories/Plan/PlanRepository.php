<?php

namespace App\Repositories\Plan;

use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PlanRepository implements PlanRepositoryInterface
{
    private function normalizeActiveStatus(mixed $value): string
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

    // create plan in db
    public function create(array $data)
    {
        $plan = Plan::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'pricing' => $data['pricing'],
            'duration' => $data['duration'],
            'is_active' => array_key_exists('is_active', $data)
                ? $this->normalizeActiveStatus($data['is_active'])
                : 'true',
            'admin_id' => Auth::id()
        ]);

        return $plan;
    }

    public function toggleStatus(int $id)
    {
        $plan = Plan::withCount('subscriptions')->findOrFail($id);

        // Do not allow deactivating a plan that already has subscribers.
        if ($plan->is_active === 'true' && $plan->subscriptions_count > 0) {
            return [
                'success' => false,
                'message' => 'You cannot deactivate this plan because it already has users.',
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

    public function destroy(int $id)
    {
        return Plan::destroy($id);
    }
}
