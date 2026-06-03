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
            'admin_id' => Auth::id()
        ]);

        return $plan;
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
