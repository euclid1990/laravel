<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Mockery as m;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use InvalidArgumentException;

class UserHasRolesAndPermissionsTest extends TestCase
{
    use DatabaseTransactions;

    public function testAssignRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class, 4)->create();

        $result = $user->assignRole($role->pluck('name')->toArray());

        $this->assertEquals($result['attached'], $role->pluck('id')->intersect($result['attached'])->toArray());
    }

    public function testRemoveRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class, 4)->create();

        $assignRole = $user->assignRole($role);

        $this->assertEquals($assignRole['attached'], $role->pluck('id')->intersect($assignRole['attached'])->toArray());

        $resultNumber = $user->removeRole($role);

        $this->assertEquals(4, $resultNumber);
        $this->assertEquals([], $role->pluck('id')->intersect($user->roles->pluck('id')->toArray())->toArray());
    }

    public function testSyncRoles()
    {
        $user = factory(User::class)->create();
        $roleDetach = factory(Role::class, 4)->create();
        $roleAttach = factory(Role::class, 4)->create();

        $attachRoleDetach = $user->assignRole($roleDetach);

        $this->assertEquals($attachRoleDetach['attached'], $roleDetach->pluck('id')->intersect($attachRoleDetach['attached'])->toArray());

        $syncRoleAttach = $user->syncRoles($roleAttach);

        $this->assertEquals($syncRoleAttach['attached'], $roleAttach->pluck('id')->intersect($syncRoleAttach['attached'])->toArray());
        $this->assertEquals($syncRoleAttach['detached'], []);
    }

    public function testFindPermissionByName()
    {
        $user = factory(User::class)->create();
        $permission = factory(Permission::class)->create();

        $result = $user->findPermissionByName($permission->name);

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testFindPermissionByNameThrowPermissionNotExist()
    {
        $permissionNameNotExist = 'permissionNameNotExist';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(__('exception.there_no_permission_name', ['permissionName' => $permissionNameNotExist]));

        $user = factory(User::class)->create();

        $user->findPermissionByName($permissionNameNotExist);
    }

    public function testFindPermissionById()
    {
        $user = factory(User::class)->create();
        $permission = factory(Permission::class)->create();

        $result = $user->findPermissionById($permission->id);

        $this->assertEquals($permission->toArray(), $result->toArray());
    }

    public function testFindPermissionByIdThrowPermissionNotExist()
    {
        $permissionIdNotExist = 0;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(__('exception.there_no_permission_id', ['permissionId' => $permissionIdNotExist]));

        $user = factory(User::class)->create();

        $user->findPermissionById($permissionIdNotExist);
    }

    public function testFindRoleByName()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $result = $user->findRoleByName($role->name);

        $this->assertEquals($role->toArray(), $result->toArray());
    }

    public function testFindRoleByNameThrowRoleNotExist()
    {
        $roleNameNotExist = 'permissionNameNotExist';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(__('exception.there_no_role_name', ['roleName' => $roleNameNotExist]));

        $user = factory(User::class)->create();

        $user->findRoleByName($roleNameNotExist);
    }

    public function testFindRoleById()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $result = $user->findRoleById($role->id);

        $this->assertEquals($role->toArray(), $result->toArray());
    }

    public function testFindRoleByIdThrowRoleNotExist()
    {
        $roleIdNotExist = 0;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(__('exception.there_no_role_id', ['roleId' => $roleIdNotExist]));

        $user = factory(User::class)->create();

        $user->findRoleById($roleIdNotExist);
    }
    // Test hasRole
    public function testHasRolesWhenUserHasThatRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->assignRole($role);

        $this->assertEquals(true, $user->hasRole($role));
    }

    public function testHasRolesWhenUserHasThoseRoles()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class, 4)->create();

        $user->assignRole($role);

        $this->assertEquals(true, $user->hasRole($role));
    }

    public function testHasRolesWithRoleIdWhenUserHasThatRole()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->assignRole($role);

        $this->assertEquals(true, $user->hasRole($role->id));
    }

    public function testHasRolesWithRoleIdsWhenUserHasThoseRoles()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class, 4)->create();

        $user->assignRole($role);

        $this->assertEquals(true, $user->hasRole($role->pluck('id')->toArray()));
    }

    public function testHasRolesWithRoleNamesStringWhenUserNotHaveAnyRoles()
    {
        $user = factory(User::class)->create();

        $roleText = 'roleNameNotExist|anothorRoleNameNotExist';

        $this->assertEquals(false, $user->hasRole($roleText));
    }

    public function testHasRolesWithRoleNameNotExist()
    {
        $user = factory(User::class)->create();

        $roleText = 'roleNameNotExist';

        $this->assertEquals(false, $user->hasRole($roleText));
    }

    public function testHasRolesWithRoleNameNotAssign()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertEquals(false, $user->hasRole($role->name));
    }
    // Test hasPermission
    public function testHasPermissionWhenUserHasThatPermission()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class)->create();

        $user->assignRole($role);
        $role->assignPermission($permision);

        $this->assertEquals(true, $user->hasPermission($permision));
    }

    public function testHasPermissionWhenUserNotHasThatPermission()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class)->create();

        $user->assignRole($role);

        $this->assertEquals(false, $user->hasPermission($permision));
    }

    public function testHasPermissionWithPermissionNameWhenUserHasThatPermission()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class)->create();

        $user->assignRole($role);
        $role->assignPermission($permision);

        $this->assertEquals(true, $user->hasPermission($permision->name));
    }

    public function testHasPermissionWithPermissionIdWhenUserHasThatPermission()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class)->create();

        $user->assignRole($role);
        $role->assignPermission($permision);

        $this->assertEquals(true, $user->hasPermission($permision->id));
    }

    public function testHasPermissionsWhenUserHasThosePermissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class, 5)->create();

        $user->assignRole($role);
        $role->assignPermission($permision);

        $this->assertEquals(true, $user->hasPermission($permision));
    }

    public function testHasPermissionsWithNameArrayWhenUserHasThosePermissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class, 5)->create();

        $user->assignRole($role);
        $role->assignPermission($permision);

        $this->assertEquals(true, $user->hasPermission($permision->pluck('name')->toArray()));
    }

    public function testHasPermissionsWithIdArrayWhenUserHasThosePermissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class, 5)->create();

        $user->assignRole($role);
        $role->assignPermission($permision);

        $this->assertEquals(true, $user->hasPermission($permision->pluck('id')->toArray()));
    }

    public function testHasPermissionsWithCustomArrayWhenUserHasThosePermissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $permision = factory(Permission::class, 5)->create();

        $user->assignRole($role);
        $role->assignPermission($permision);

        $arr = ['id', 'name'];
        $customArray = [];
        foreach ($permision as $perm) {
            $customArray[] = $perm[$arr[array_rand($arr)]];
        }
        $this->assertEquals(true, $user->hasPermission($customArray));
    }
}
