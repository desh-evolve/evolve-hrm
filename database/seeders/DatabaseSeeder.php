<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Register your seeder here
        $this->call(IndustrySeeder::class);

        $this->call([
            IndustrySeeder::class,
            EmpEmployeesTableSeeder::class,
            ComWageTypesTableSeeder::class,
            MessageTypesTableSeeder::class,
        ]);
    }
}
