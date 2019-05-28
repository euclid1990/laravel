<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = $role = [];
        
        foreach (config('permission.permission') as $value) {
            foreach ($value as $v) {
                $permission[] = ['name' => $v];
            }
        }
        foreach (config('permission.role') as $key => $value) {
            $role[] = ['name' => $value];
        }

        Role::insert($role);
        Permission::insert($permission);

        foreach (config('permission.role_permission') as $key => $value) {
            $role = Role::where('name', $key)->first();
            foreach ($value as $v) {
                $role->assignPermission(config('permission.permission.' . $v));
            }
            
        }
        $users = User::get();
        foreach ($users as $key => $user) {
            $user->assignRole(Role::all()->random());
        }
    }
}
