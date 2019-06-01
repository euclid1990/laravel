<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RoleHasPermissions;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Exceptions\Permission\RoleDoesNotExist;

class Role extends BaseModel
{
    use RoleHasPermissions;

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_roles',
            'role_id',
            'user_id'
        );
    }
}
