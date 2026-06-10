<?php

namespace App\Interfaces\Project;

interface ProjectRepositoryInterface
{
    public function allForUser(int $userId);
    public function countForUser(int $userId): int;
    public function create(array $data);
    public function findForUser(int $projectId, int $userId);
    public function update(int $projectId, array $data);
    public function delete(int $projectId, int $userId): void;
}
