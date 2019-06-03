<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\Permission\RoleMiddleware;
use App\Models\User;
use App\Models\Role;
use App\Exceptions\Permission\UnauthorizedException;

class RoleMiddlewareTest extends TestCase
{
    protected $roleMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleMiddleware = new RoleMiddleware($this->app);
    }

    public function testGuestCanNotAccessRouteHasRoleMiddleware()
    {
        $this->assertEquals($this->runMiddleware($this->roleMiddleware, 'testRole'), 403);
    }

    public function testUserCanAccessRouteHasRoleMiddleware()
    {
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();
        $user->assignRole($role);

        $this->actingAs($user);

        $this->assertEquals($this->runMiddleware($this->roleMiddleware, $role->name), 200);
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
