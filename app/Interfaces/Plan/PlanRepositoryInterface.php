<?php

namespace App\Interfaces\Plan;

interface PlanRepositoryInterface
{
    // add plan in db
    public function create(array $data);

    // update plan
    public function update(int $id, array $data);

    // toggle active status
    public function status(int $id);

    // delete plan
    public function destroy(int $id);

}
