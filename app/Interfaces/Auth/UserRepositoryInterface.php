<?php

namespace App\Interfaces\Auth;

interface UserRepositoryInterface
{
    // register user in db
    public function create(array $data);

    // find register user from db
    public function findByEmail(string $email);
}
