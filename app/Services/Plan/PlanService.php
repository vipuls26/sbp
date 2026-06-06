<?php

namespace App\Services\Plan;

use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Models\Plan;

class PlanService
{
    public function __construct(
        private PlanRepositoryInterface $planRepositoryInterface
    ) {}

    // show plans to the admin dashboard
    public function dashboard(): array
    {
        return [
            'plans' => Plan::withCount('subscriptions')->get(),
        ];
    }
    // create a plan
    public function create(array $data)
    {
        return $this->planRepositoryInterface->create($data);
    }

    // update a plan
    public function update(int $id, array $data)
    {
        return $this->planRepositoryInterface->update($id, $data);
    }

    // toggle active/deactive status
    public function toggleStatus(int $id)
    {
        return $this->planRepositoryInterface->toggleStatus($id);
    }

    // delete a plan
    public function delete(int $id)
    {
        return $this->planRepositoryInterface->delete($id);
    }
}
