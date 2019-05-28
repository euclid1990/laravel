<?php

namespace App\Exceptions\Permission;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends HttpException
{
    public static function forRoles(array $roles): self
    {
        $permStr = implode(', ', $roles);
        $message = __('exception.not_have_role', ['roles' => $permStr]);

        $exception = new static(Response::HTTP_FORBIDDEN, $message, null, []);

        return $exception;
    }

    public static function forPermissions(array $permissions): self
    {
        $permStr = implode(', ', $permissions);
        $message = __('exception.not_have_permission', ['permissions' => $permStr]);

        $exception = new static(Response::HTTP_FORBIDDEN, $message, null, []);

        return $exception;
    }

    public static function forRolesOrPermissions(array $rolesOrPermissions): self
    {
        $permStr = implode(', ', $rolesOrPermissions);
        $message = __('exception.not_have_role_and_permission', ['permissions' => $permStr]);

        $exception = new static(Response::HTTP_FORBIDDEN, $message, null, []);

        return $exception;
    }

    public static function notLoggedIn(): self
    {
        return new static(Response::HTTP_FORBIDDEN, __('exception.not_loggin'), null, []);
    }
}
