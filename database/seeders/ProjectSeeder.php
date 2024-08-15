<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            DB::table('projects')->insert([
                'uuid' => $faker->uuid,
                'name' => $faker->sentence(3),
                'description' => $faker->paragraph, 
            ]);
        }
    }
}
