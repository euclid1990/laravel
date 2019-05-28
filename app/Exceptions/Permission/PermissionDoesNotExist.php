<?php

namespace App\Exceptions\Permission;

use InvalidArgumentException;

class PermissionDoesNotExist
{
    public static function create(string $permissionName)
    {
        return new InvalidArgumentException(__('exception.there_no_permission_name', ['permissionName' => $permissionName]));
    }

    public static function withId(int $permissionId)
    {
        return new InvalidArgumentException(__('exception.there_no_permission_id', ['permissionId' => $permissionId]));
    }
}
