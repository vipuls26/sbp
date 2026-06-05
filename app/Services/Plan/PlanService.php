<?php

namespace App\Services\Plan;

use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Models\Plan;

class PlanService
{
    public function __construct(
        private PlanRepositoryInterface $planRepositoryInterface
    ) {}

    // show plan to admin
    public function dashboard(): array
    {
        return [
            'plans' => Plan::withCount('subscriptions')->get(),
        ];
    }
    // create plan
    public function add(array $data)
    {
        return $this->planRepositoryInterface->create($data);
    }

    // update plan
    public function update(int $id, array $data)
    {
        return $this->planRepositoryInterface->update($id, $data);
    }

    // active deactice
    public function status(int $id)
    {
        return $this->planRepositoryInterface->status($id);
    }

    // delete plan
    public function destroy(int $id)
    {
        return $this->planRepositoryInterface->destroy($id);
    }
}
