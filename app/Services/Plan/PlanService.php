<?php

namespace App\Services\Plan;

use App\Interfaces\Plan\PlanRepositoryInterface;


class PlanService
{
    public function __construct(
        private PlanRepositoryInterface $planRepository
    ) {}

    // create plan
    public function add(array $data)
    {
        return $this->planRepository->create($data);
    }

    public function update(int $id,array $data)
    {
        return $this->planRepository->update($id, $data);
    }

    public function destroy(int $id)
    {
        return $this->planRepository->destroy($id);
    }
}
