<?php

namespace App\Repositories\Admin;

use App\Interfaces\Admin\AdminRepositoryInterface;
use App\Models\User;

class AdminRepository implements AdminRepositoryInterface
{
    // block user
    public function block(int $id)
    {
        $user = User::destroy($id);
        return $user;
    }

    public function unblock(int $id)
    {

        $user = User::withTrashed()->findOrFail($id)->restore();
        return $user;
    }
}
