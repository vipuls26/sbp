<?php

namespace App\Services\Plan;

use App\Interfaces\Plan\PlanRepositoryInterface;

class PlanService
{
    public function __construct(
        private PlanRepositoryInterface $planRepositoryInterface
    ) {}

    // create plan
    public function add(array $data)
    {
        return $this->planRepositoryInterface->create($data);
    }

    public function update(int $id,array $data)
    {
        return $this->planRepositoryInterface->update($id, $data);
    }

    public function toggleStatus(int $id)
    {
        return $this->planRepositoryInterface->toggleStatus($id);
    }

    public function destroy(int $id)
    {
        return $this->planRepositoryInterface->destroy($id);
    }
}
