<?php

namespace App\Repo;

use App\Models\Role;

class RoleRepository extends AppRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
