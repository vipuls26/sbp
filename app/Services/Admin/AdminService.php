<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\AdminRepositoryInterface;
use App\Models\Subscription;

class AdminService
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {}

    // block user
    public function block(int $id)
    {
        return $this->adminRepository->block($id);
    }

    // unblock user
    public function unblock(int $id)
    {
        return $this->adminRepository->unblock($id);
    }

    public function subscriber()
    {
        return Subscription::with(['plan','user'])->get();
    }
}
