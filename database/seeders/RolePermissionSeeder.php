<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'user']);

        Permission::create(['name' => 'permission-index']);
        Permission::create(['name' => 'permission-add']);
        Permission::create(['name' => 'permission-edit']);
        Permission::create(['name' => 'permission-delete']);

        Permission::create(['name' => 'user-index']);
        Permission::create(['name' => 'user-add']);
        Permission::create(['name' => 'user-edit']);
        Permission::create(['name' => 'user-delete']);

        Permission::create(['name' => 'project-index']);
        Permission::create(['name' => 'project-add']);
        Permission::create(['name' => 'project-edit']);
        Permission::create(['name' => 'project-delete']);

        Permission::create(['name' => 'task-index']);
        Permission::create(['name' => 'task-add']);
        Permission::create(['name' => 'task-edit']);
        Permission::create(['name' => 'task-delete']);

        Role::findByName('superadmin')->givePermissionTo([
            'permission-index',
            'permission-add',
            'permission-edit',
            'permission-delete',
            'user-index',
            'user-add',
            'user-edit',
            'user-delete',
            'project-index',
            'project-add',
            'project-edit',
            'project-delete',
            'task-index',
            'task-add',
            'task-edit',
            'task-delete',
        ]);
    }
}
