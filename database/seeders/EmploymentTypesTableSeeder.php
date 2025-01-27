<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the data into the employment_types table
        DB::table('employment_types')->insert([
            ['id' => 1, 'name' => 'Contract', 'is_duration' => 1],
            ['id' => 2, 'name' => 'Training', 'is_duration' => 1],
            ['id' => 3, 'name' => 'Permanent (With Probation)', 'is_duration' => 1],
            ['id' => 4, 'name' => 'Permanent (Confirmed)', 'is_duration' => 0],
            ['id' => 5, 'name' => 'Resign', 'is_duration' => 0],
            ['id' => 6, 'name' => 'External', 'is_duration' => 0],
        ]);
    }
}
