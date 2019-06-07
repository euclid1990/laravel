<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\Permission;
use Tests\ModelTestCase;

class PermissionTest extends ModelTestCase
{
    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(new Permission());
    }

    public function testRolesRelation()
    {
        $m = new Permission();
        $r = $m->roles();
        $this->assertBelongsToManyRelation($r, $m, new Role(), 'permission_id', 'role_id');
    }
}
