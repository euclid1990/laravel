<?php

namespace App\Permission;

use Illuminate\Support\Collection;
use App\Models\User;
use Illuminate\Contracts\Auth\Access\Gate;
use App\Exceptions\Permission\PermissionDoesNotExist;

class PermissionRegister
{
    /** @var \Illuminate\Contracts\Auth\Access\Gate */
    protected $gate;

    /**
     * PermissionRegister constructor.
     *
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Register the permission check method on the gate.
     *
     * @return bool
     */
    public function registerPermissions(): bool
    {
        $this->gate->before(function (User $user, string $ability) {
            try {
                if (method_exists($user, 'hasPermissionTo')) {
                    return $user->hasPermissionTo($ability) ?: null;
                }
            } catch (PermissionDoesNotExist $e) {
            }
        });

        return true;
    }
}
