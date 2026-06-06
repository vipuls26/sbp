<?php

namespace App\Interfaces\Admin;

interface AdminRepositoryInterface
{
    // block user
    public function block(int $id);

    // restore a blocked user
    public function restore(int $id);
}
