<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\AdminRepositoryInterface;

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
}
