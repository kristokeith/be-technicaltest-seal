<?php

namespace Database\Seeders;

use AreasTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProjectSeeder::class,
            TaskSeeder::class,
            UserSeeder::class,
        ]);
    }
}
