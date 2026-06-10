<?php

namespace App\Interfaces\Team;

interface TeamRepositoryInterface
{
    public function membersForOwner(int $ownerId);
    public function addMember(array $data);
    public function removeMember(int $memberId, int $ownerId): void;
}
