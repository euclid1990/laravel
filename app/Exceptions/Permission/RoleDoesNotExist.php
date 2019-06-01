<?php

namespace App\Exceptions\Permission;

use InvalidArgumentException;

class RoleDoesNotExist
{
    public static function named(string $roleName)
    {
        return new InvalidArgumentException(__('exception.there_no_role_name', ['roleName' => $roleName]));
    }

    public static function withId(int $roleId)
    {
        return new InvalidArgumentException(__('exception.there_no_role_name', ['roleId' => $roleId]));
    }
}
