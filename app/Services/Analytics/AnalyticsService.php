<?php

namespace App\Services\Analytics;

use App\Models\Project;

class AnalyticsService
{
    public function stats(int $userId): array
    {
        $projects = Project::where('user_id', $userId)->get();

        return [
            'total'     => $projects->count(),
            'active'    => $projects->where('status', 'active')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
            'on_hold'   => $projects->where('status', 'on_hold')->count(),
        ];
    }
}
