<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('tasks')->insert([
                'uuid' => $faker->uuid,
                'project_uuid' => null,
                'name' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'due_date' => $faker->dateTimeBetween('now', '+1 year'),
            ]);
        }
    }
}
