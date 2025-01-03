<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Register all seeders in an array
        $this->call([
            UserRolePermissionSeeder::class,
            IndustrySeeder::class,
            ComWageTypesTableSeeder::class,
            ObjectTypeSeeder::class,
            OverTimePolicyTypesSeeder::class,
            RoundIntervalPunchTypesSeeder::class,
            TimeZoneSeeder::class,
        ]);
    }
}
