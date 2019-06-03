<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Mockery as m;
use App\Models\Role;
use App\Models\Permission;

class RoleHasPermissionsTest extends TestCase
{
    public function testAssignPermissionWithPermissionName()
    {
        $role = factory(Role::class, 1)->create();
        $permission = factory(Permission::class, 1)->create();
        $result = $role[0]->assignPermission($permission[0]->name);

        $this->assertEquals(true, in_array($permission[0]->id, $result['attached']));
    }

    public function testAssignPermissionWithPermissionId()
    {
        $role = factory(Role::class, 1)->create();
        $permission = factory(Permission::class, 1)->create();
        $result = $role[0]->assignPermission($permission[0]->id);

        $this->assertEquals(true, in_array($permission[0]->id, $result['attached']));
    }

    public function testAssignPermissionWithPermissionObject()
    {
        $role = factory(Role::class, 1)->create();
        $permission = factory(Permission::class, 1)->create();
        $result = $role[0]->assignPermission($permission[0]);

        $this->assertEquals(true, in_array($permission[0]->id, $result['attached']));
    }

    public function testAssignPermissionWithPermissionCollection()
    {
        $role = factory(Role::class, 1)->create();
        $permission = factory(Permission::class, 4)->create();
        $result = $role[0]->assignPermission($permission);

        $this->assertEquals($result['attached'], $permission->pluck('id')->intersect($result['attached'])->toArray());
    }
}
