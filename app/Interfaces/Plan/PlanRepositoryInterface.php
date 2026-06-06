<?php

namespace App\Interfaces\Plan;

interface PlanRepositoryInterface
{
    // create a plan record
    public function create(array $data);

    // update a plan record
    public function update(int $id, array $data);

    // toggle active status
    public function toggleStatus(int $id);

    // delete a plan record
    public function delete(int $id);

}
