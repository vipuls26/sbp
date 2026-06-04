<?php

namespace App\Repositories\Admin;

use App\Interfaces\Admin\AdminRepositoryInterface;
use App\Models\User;

class AdminRepository implements AdminRepositoryInterface
{
    // block user
    public function block(int $id)
    {
        return User::destroy($id);
    }
}
