<?php

namespace App\Services\Project;

use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Models\User;

class ProjectService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository
    ) {}

    public function list(int $userId)
    {
        return $this->projectRepository->allForUser($userId);
    }

    public function create(User $user, array $data): array
    {
        $limit = $user->projectLimit();
        $count = $this->projectRepository->countForUser($user->id);

        if ($limit !== PHP_INT_MAX && $count >= $limit) {
            return ['success' => false, 'message' => "Your plan allows a maximum of {$limit} projects."];
        }

        $this->projectRepository->create(array_merge($data, ['user_id' => $user->id]));

        return ['success' => true];
    }

    public function find(int $projectId, int $userId)
    {
        return $this->projectRepository->findForUser($projectId, $userId);
    }

    public function update(int $projectId, array $data)
    {
        return $this->projectRepository->update($projectId, $data);
    }

    public function delete(int $projectId, int $userId): void
    {
        $this->projectRepository->delete($projectId, $userId);
    }
}
