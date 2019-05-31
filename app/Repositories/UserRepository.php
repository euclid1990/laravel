<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends AppRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUserByToken($token)
    {
        return $this->model
            ->resetPasswordToken($token)
            ->first();
    }

    public function getUserByEmail($email)
    {
        return $this->model
            ->where('email', $email)
            ->first();
    }
}
