<?php

namespace App\Repo;

use App\Models\User;

class UserRepository extends AppRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
