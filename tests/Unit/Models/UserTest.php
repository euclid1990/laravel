<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Role;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    public function testModelConfiguration()
    {
        $this->runConfigurationAssertions(
            new User(),
            [
                'name',
                'email',
                'password',
            ],
            [
                'password',
                'remember_token',
            ],
            ['*'],
            [],
            [
                'id' => 'int',
                'email_verified_at' => 'datetime',
            ]
        );
    }

    public function testRolesRelation()
    {
        $m = new User();
        $r = $m->roles();
        $this->assertBelongsToManyRelation($r, $m, new Role(), 'user_id', 'role_id');
    }
}
