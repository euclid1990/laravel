<?php

namespace App\Http\Middleware\Permission;

use Closure;
use App\Exceptions\Permission\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, ...$permissions)
    {
        if (app('auth')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (!auth()->user()->hasPermission($permissions)) {
            throw UnauthorizedException::forPermissions($permissions);
        }

        return $next($request);
    }
}
