<?php

namespace App\Traits;

use App\Models\Permission;
use App\Exceptions\Permission\PermissionDoesNotExist;

trait RoleHasPermissions
{
    /**
     * Grant the given permission(s) to role.
     *
     * @param string|array|\App\Models\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return array
     */
    public function assignPermission(...$permissions): array
    {
        $permissions = collect($permissions)
            ->flatten()
            ->map(function ($permission) {
                if (empty($permission)) {
                    return false;
                }

                return $this->getStoredPermission($permission);
            })
            ->filter(function ($permission) {
                return $permission instanceof Permission;
            })
            ->pluck('id');

        return $this->permissions()->sync($permissions, false);
    }

    /**
     * Remove all current permissions and set the given ones.
     *
     * @param string|array|\App\Models\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return array
     */
    public function syncPermissions(...$permissions): array
    {
        $this->permissions()->detach();

        return $this->assignPermission($permissions);
    }

    /**
     * Revoke the given permission.
     *
     * @param \App\Models\Permission|\App\Models\Permission[]|string|string[] $permission
     *
     * @return int
     */
    public function revokePermissionTo($permission): int
    {
        return $this->permissions()->detach($this->getStoredPermission($permission));
    }

    /**
     * Find a permission by its name
     *
     * @param string $name
     *
     * @throws \App\Exceptions\Permission\PermissionDoesNotExist
     *
     * @return \App\Models\Permission
     */
    public function findByName(string $name): Permission
    {
        $permission = Permission::where('name', $name)->first();
        if (!$permission) {
            throw PermissionDoesNotExist::create($name);
        }

        return $permission;
    }

    /**
     * Find a permission by its id
     *
     * @param int $id
     *
     * @throws \App\Exceptions\Permission\PermissionDoesNotExist
     *
     * @return \App\Models\Permission
     */
    public function findById(int $id): Permission
    {
        $permission = Permission::find($id);

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id);
        }

        return $permission;
    }

    /**
     * Find a permission by its name
     *
     * @param int|string $permission
     *
     * @return \App\Models\Permission
     *
     */
    public function getStoredPermission($permissions): Permission
    {
        if (is_numeric($permissions)) {
            return $this->findById($permissions);
        }

        if (is_string($permissions)) {
            return $this->findByName($permissions);
        }

        if (is_array($permissions)) {
            return Permission::whereIn('name', $permissions)->get();
        }

        return $permissions;
    }
}
