<?php

namespace App\Http\Middleware\Permission;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\Permission\UnauthorizedException;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (!Auth::user()->hasRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
