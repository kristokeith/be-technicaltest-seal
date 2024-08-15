<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;
use App\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'user']);


        $modelNames = ['permission', 'user', 'role', 'task', 'project'];
        $actions = ['index', 'add', 'edit', 'delete'];

        foreach ($modelNames as $modelName) {
            foreach ($actions as $action) {
                Permission::create(['name' => $modelName . '-' . $action]);
                Role::findByName('super-admin')->givePermissionTo($modelName . '-' . $action);
            }
        }

        $modelNames = ['task', 'project'];
        $actions = ['index', 'add', 'edit', 'delete'];

        foreach ($modelNames as $modelName) {
            foreach ($actions as $action) {
                Role::findByName('user')->givePermissionTo($modelName . '-' . $action);
            }
        }
        Role::findByName('user')->givePermissionTo('user-index', 'user-edit');

        $faker = Faker::create();

        $superadmin = User::create([
            'name' => 'superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'no_hp' => $faker->phoneNumber,
            'profile_photo' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
            'task_uuid' => null,
        ])->assignRole('super-admin');

        for ($i = 0; $i < 15; $i++) {
            User::create([
                'name' => $faker->name,
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'no_hp' => $faker->phoneNumber,
                'profile_photo' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
                'task_uuid' => null,
            ])->assignRole('user');
        }
    }
}
