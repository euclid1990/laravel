<?php

namespace App\Http\Middleware\Permission;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\Permission\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role) ? $role : explode('|', $role);

        if (!Auth::user()->hasRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
