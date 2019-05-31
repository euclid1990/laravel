<?php

namespace App\Repositories;

interface UserRepositoryInterface extends AppRepositoryInterface
{
    public function getUserByToken($token);

    public function getUserByEmail($email);
}
