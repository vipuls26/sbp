<?php

namespace App\Services\Team;

use App\Interfaces\Team\TeamRepositoryInterface;

class TeamService
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository
    ) {}

    public function members(int $ownerId)
    {
        return $this->teamRepository->membersForOwner($ownerId);
    }

    public function addMember(int $ownerId, array $data)
    {
        return $this->teamRepository->addMember(array_merge($data, ['owner_id' => $ownerId]));
    }

    public function removeMember(int $memberId, int $ownerId): void
    {
        $this->teamRepository->removeMember($memberId, $ownerId);
    }
}
