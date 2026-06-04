<?php

namespace App\Interfaces\Admin;

interface AdminRepositoryInterface
{
    // block user
    public function block(int $id);
}
