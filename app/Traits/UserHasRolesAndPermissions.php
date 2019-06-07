<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use App\Exceptions\Permission\RoleDoesNotExist;
use App\Exceptions\Permission\PermissionDoesNotExist;

trait UserHasRolesAndPermissions
{
    /**
     * Assign the given role to user.
     *
     * @param array|string|\App\Models\Role ...$roles
     *
     * @return
     */
    public function assignRole(...$roles): array
    {
        $roles = collect($roles)
            ->flatten()
            ->map(function ($role) {
                if (empty($role)) {
                    return false;
                }

                return $this->getStoredRole($role);
            })
            ->filter(function ($role) {
                return $role instanceof Role;
            })
            ->pluck('id');

        return $this->roles()->sync($roles, false);
    }

    /**
     * Revoke the given role from user.
     *
     * @param string|\App\Models\Role $role
     *
     * @return int
     */
    public function removeRole($role): int
    {
        return $this->roles()->detach($this->getStoredRole($role));
    }

    /**
     * Remove all current roles and set the given ones.
     *
     * @param array|\App\Models\Role|string ...$roles
     *
     * @return array
     */
    public function syncRoles(...$roles): array
    {
        $this->roles()->detach();

        return $this->assignRole($roles);
    }

    /**
     * Determine if user has (one of) the given role(s).
     *
     * @param string|int|array|\App\Models\Role|\Illuminate\Support\Collection $roles
     *
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if (is_int($roles)) {
            return $this->roles->contains('id', $roles);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains('id', $roles->id);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }

            return false;
        }

        if ($roles instanceof \Illuminate\Support\Collection) {
            return $roles->intersect($this->roles)->isNotEmpty();
        }

        return false;
    }

    /**
     * Determine if user has all the given role(s).
     *
     * @param string|int|array|\App\Models\Role|\Illuminate\Support\Collection $roles
     *
     * @return bool
     */
    public function hasAllRoles($roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if (is_int($roles)) {
            return $this->roles->contains('id', $roles);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains('id', $roles->id);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (!$this->hasAllRoles($role)) {
                    return false;
                }
            }

            return true;
        }

        if ($roles instanceof \Illuminate\Support\Collection) {
            return $roles->intersect($this->roles)->pluck('id') == $this->roles->pluck('id');
        }

        return false;
    }

    /**
     * Determine if user may perform any of the given permission(s).
     *
     * @param string|int|\App\Models\Permission $permission
     *
     * @return bool
     */
    public function hasPermission($permissions): bool
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->hasPermission($permission)) {
                    return true;
                }
            }

            return false;
        }

        if (is_string($permissions)) {
            return $this->permissions->contains('name', $permissions);
        }

        if (is_int($permissions)) {
            return $this->permissions->contains('id', $permissions);
        }

        if ($permissions instanceof Permission) {
            return $this->permissions->contains('id', $permissions->id);
        }

        if ($permissions instanceof \Illuminate\Support\Collection) {
            return $permissions->intersect($this->permissions)->isNotEmpty();
        }

        return false;
    }

    /**
     * Determine if user may perform all of the given permissions.
     *
     * @param string|int|\App\Models\Permission $permission
     *
     * @return bool
     */
    public function hasAllPermissions($permissions): bool
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if (!$this->hasAllPermissions($permission)) {
                    return false;
                }
            }

            return true;
        }

        if (is_string($permissions)) {
            return $this->permissions->contains('name', $permissions);
        }

        if (is_int($permissions)) {
            return $this->permissions->contains('id', $permissions);
        }

        if ($permissions instanceof Permission) {
            return $this->permissions->contains('id', $permissions->id);
        }

        if ($permissions instanceof \Illuminate\Support\Collection) {
            return $permissions->intersect($this->permissions)->pluck('id') == $this->permissions->pluck('id');
        }

        return false;
    }

    /**
     * Find a permission by its name
     *
     * @param string $name
     *
     * @throws \App\Exceptions\Permission\PermissionDoesNotExist
     */
    public function findPermissionByName(string $name): Permission
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
    public function findPermissionById(int $id): Permission
    {
        $permission = Permission::find($id);

        if (!$permission) {
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
    public function getStoredPermission($permissions)
    {
        if (is_numeric($permissions)) {
            return $this->findPermissionById($permissions);
        }

        if (is_string($permissions)) {
            return $this->findPermissionByName($permissions);
        }

        if (is_array($permissions) && !empty($permissions)) {
            if (is_array_number($permissions)) {
                return Permission::whereIn('id', $permissions)->get();
            }

            if (is_array_string($permissions)) {
                return Permission::whereIn('name', $permissions)->get();
            }
        }

        return $permissions;
    }

    /**
     * Find a role by its name
     *
     * @param string $name
     *
     * @return \App\Models\Role
     *
     * @throws \App\Exceptions\Permission\RoleDoesNotExist
     */
    public function findRoleByName(string $name): Role
    {
        $role = Role::where('name', $name)->first();

        if (!$role) {
            throw RoleDoesNotExist::named($name);
        }

        return $role;
    }

    /**
     * Find a role by its id
     *
     * @param int $id
     *
     * @throws \App\Exceptions\Permission\PermissionDoesNotExist
     *
     * @return \App\Models\Role
     */
    public function findRoleById(int $id): Role
    {
        $role = Role::find($id);

        if (!$role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * Find a role by its name
     *
     * @param int|string $role
     *
     * @return \App\Models\Role
     *
     */
    public function getStoredRole($role)
    {
        if (is_numeric($role)) {
            return $this->findRoleById($role);
        }

        if (is_string($role)) {
            return $this->findRoleByName($role);
        }

        return $role;
    }
}
