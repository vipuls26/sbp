<?php

namespace App\Repositories\Team;

use App\Interfaces\Team\TeamRepositoryInterface;
use App\Models\TeamMember;

class TeamRepository implements TeamRepositoryInterface
{
    public function membersForOwner(int $ownerId)
    {
        return TeamMember::where('owner_id', $ownerId)->latest()->get();
    }

    public function addMember(array $data)
    {
        return TeamMember::create($data);
    }

    public function removeMember(int $memberId, int $ownerId): void
    {
        TeamMember::where('id', $memberId)->where('owner_id', $ownerId)->delete();
    }
}
