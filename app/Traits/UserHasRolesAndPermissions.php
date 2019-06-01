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
        if (is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }

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

        if ($roles instanceof \Illuminate\Database\Eloquent\Collection) {
            return $roles->intersect($this->roles)->isNotEmpty();
        }

        return false;
    }

    /**
     * Determine if user has all of the given role(s).
     *
     * @param string|\App\Models\Role|\Illuminate\Support\Collection $roles
     *
     * @return bool
     */
    public function hasAllRoles($roles): bool
    {
        if (is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }

        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains('id', $roles->id);
        }

        $roles = collect()->make($roles)->map(function ($role) {
            return $role instanceof Role ? $role->name : $role;
        });

        return $roles->intersect($this->roles->pluck('name')) == $roles;
    }

    /**
     * Determine if user may perform the given permission.
     *
     * @param string|int|\App\Models\Permission $permission
     *
     * @return bool
     */
    public function hasPermissionTo($permission): bool
    {
        $permission = $this->getStoredPermission($permission);

        if (!$permission instanceof Permission) {
            return false;
        }

        return $this->hasPermissionViaRole($permission);
    }

    /**
     * Determine if user has any of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     */
    public function hasAnyPermission(...$permissions): bool
    {
        if (is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if user has all of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     */
    public function hasAllPermissions(...$permissions): bool
    {
        if (is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermissionTo($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if user has the given permission via roles.
     *
     * @param \App\Models\Permission $permission
     *
     * @return bool
     */
    public function hasPermissionViaRole(Permission $permission): bool
    {
        return $this->hasRole($permission->roles);
    }

    protected function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (! in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
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
            return $this->findPermissionById($permissions);
        }

        if (is_string($permissions)) {
            return $this->findPermissionByName($permissions);
        }

        if (is_array($permissions)) {
            return Permission::whereIn('name', $permissions)->get();
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
    public function getStoredRole($role): Role
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
