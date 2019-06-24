<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\Permission\PermissionMiddleware;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Exceptions\Permission\UnauthorizedException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PermissionMiddlewareTest extends TestCase
{
    use DatabaseTransactions;
    
    protected $permissionMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->permissionMiddleware = new PermissionMiddleware($this->app);
    }

    public function testGuestCanNotAccessRouteHasPermissionMiddleware()
    {
        $this->assertEquals($this->runMiddleware($this->permissionMiddleware, 'testPermission'), 403);
    }

    public function testUserCanAccessRouteHasRoleMiddleware()
    {
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();
        $permission = factory(Permission::class)->create();
        $user->assignRole($role);
        $role->assignPermission($permission);
        $this->actingAs($user);

        $this->assertEquals($this->runMiddleware($this->permissionMiddleware, $permission->name), 200);
    }

    protected function runMiddleware($middleware, $parameter)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, $parameter)->status();
        } catch (UnauthorizedException $e) {
            return $e->getStatusCode();
        }
    }
}
