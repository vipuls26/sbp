<?php

namespace App\Repositories\Project;

use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Models\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function allForUser(int $userId)
    {
        return Project::where('user_id', $userId)->latest()->get();
    }

    public function countForUser(int $userId): int
    {
        return Project::where('user_id', $userId)->count();
    }

    public function create(array $data)
    {
        return Project::create($data);
    }

    public function findForUser(int $projectId, int $userId)
    {
        return Project::where('id', $projectId)->where('user_id', $userId)->firstOrFail();
    }

    public function update(int $projectId, array $data)
    {
        $project = Project::findOrFail($projectId);
        $project->update($data);
        return $project;
    }

    public function delete(int $projectId, int $userId): void
    {
        Project::where('id', $projectId)->where('user_id', $userId)->delete();
    }
}
