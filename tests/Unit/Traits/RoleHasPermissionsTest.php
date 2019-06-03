<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Mockery as m;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use InvalidArgumentException;

class RoleHasPermissionsTest extends TestCase
{
    use DatabaseTransactions;

    public function testassignPermissionWithPermissionName()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        $result = $role->assignPermission($permission->name);

        $this->assertEquals(true, in_array($permission->id, $result['attached']));
    }

    public function testassignPermissionWithPermissionId()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        $result = $role->assignPermission($permission->id);

        $this->assertEquals(true, in_array($permission->id, $result['attached']));
    }

    public function testassignPermissionWithPermissionObject()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        $result = $role->assignPermission($permission);

        $this->assertEquals(true, in_array($permission->id, $result['attached']));
    }

    public function testassignPermissionWithPermissionCollection()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class, 4)->create();
        $result = $role->assignPermission($permission);

        $this->assertEquals($result['attached'], $permission->pluck('id')->intersect($result['attached'])->toArray());
    }

    public function testGetStoredPermissionWithPermissionName()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $result = $role->getStoredPermission($permission->name);

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testGetStoredPermissionWithPermissionId()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $result = $role->getStoredPermission($permission->id);

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testGetStoredPermissionWithArrayId()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class, 4)->create();

        $result = $role->getStoredPermission($permission->pluck('id')->toArray());

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testGetStoredPermissionWithArrayName()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class, 4)->create();

        $result = $role->getStoredPermission($permission->pluck('name')->toArray());

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testSyncPermissions()
    {
        $role = factory(Role::class)->create();
        $permissionDetach = factory(Permission::class, 4)->create();
        $permissionAttach = factory(Permission::class, 4)->create();

        $attachPermissionDetach = $role->assignPermission($permissionDetach);

        $this->assertEquals($attachPermissionDetach['attached'], $permissionDetach->pluck('id')->intersect($attachPermissionDetach['attached'])->toArray());

        $syncPermissionAttach = $role->syncPermissions($permissionAttach);

        $this->assertEquals($syncPermissionAttach['attached'], $permissionAttach->pluck('id')->intersect($syncPermissionAttach['attached'])->toArray());
        $this->assertEquals($syncPermissionAttach['detached'], []);
    }

    public function testRevokePermissionTo()
    {
        $role = factory(Role::class)->create();
        $permissionRevoke = factory(Permission::class, 4)->create();

        $assignPermissionRevoke = $role->assignPermission($permissionRevoke);
        $this->assertEquals($assignPermissionRevoke['attached'], $permissionRevoke->pluck('id')->intersect($assignPermissionRevoke['attached'])->toArray());

        $resultNumber = $role->revokePermissionTo($permissionRevoke);

        $this->assertEquals(4, $resultNumber);
        $this->assertEquals([], $permissionRevoke->pluck('id')->intersect($role->permissions->pluck('id')->toArray())->toArray());
    }

    public function testFindPermissionByName()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $result = $role->findByName($permission->name);

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testFindPermissionByNameThrowPermissionNotExist()
    {
        $permissionNameNotExist = 'permissionNameNotExist';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(__('exception.there_no_permission_name', ['permissionName' => $permissionNameNotExist]));

        $role = factory(Role::class)->create();

        $role->findByName($permissionNameNotExist);
    }

    public function testFindPermissionById()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $result = $role->findById($permission->id);

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testFindPermissionByIdThrowPermissionNotExist()
    {
        $permissionIdNotExist = 0;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(__('exception.there_no_permission_id', ['permissionId' => $permissionIdNotExist]));

        $role = factory(Role::class)->create();

        $role->findById($permissionIdNotExist);
    }
}
