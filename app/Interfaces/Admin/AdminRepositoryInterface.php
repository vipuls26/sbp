<?php

namespace App\Interfaces\Admin;

interface AdminRepositoryInterface
{
    // block user
    public function block(int $id);

    // unblock user
    public function unblock(int $id);
}
