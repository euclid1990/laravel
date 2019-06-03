<?php

namespace Tests\Unit\Permission;

use App\Permission\PermissionRegister;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Access\Gate;
use App\Models\User;

class PermissionRegisterTest extends TestCase
{
    protected $gate;

    protected $user;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->gate = app()->make(Gate::class);
            $this->permissionRegister = new PermissionRegister($this->gate);
        });

        parent::setUp();
    }
    /**
     * Register the permission check method on the gate.
     *
     * @return bool
     */
    public function testGateBeforeCallbacksReceiveClosure()
    {
        $this->permissionRegister->registerPermissions();

        $gate = new \ReflectionClass(app()->make(Gate::class));

        $beforeCallbacks = $gate->getProperty('beforeCallbacks');
        $beforeCallbacks->setAccessible(true);
        
        $this->assertEquals(true, collect($beforeCallbacks)->isNotEmpty());
    }
}
