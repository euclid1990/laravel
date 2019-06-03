<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\ModelTestCase;

class RoleTest extends ModelTestCase
{
    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Role());
    }

    public function testUsersRelation()
    {
        $m = new Role();
        $r = $m->users();
        $this->assertBelongsToManyRelation($r, $m, new User(), 'role_id', 'user_id');
    }

    public function testPermissionsRelation()
    {
        $m = new Role();
        $r = $m->permissions();
        $this->assertBelongsToManyRelation($r, $m, new Permission(), 'role_id', 'permission_id');
    }
}
