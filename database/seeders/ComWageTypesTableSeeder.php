<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ComWageTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('com_wage_type')->insert([
            [
                'wage_type' => 'hourly',
                'name' => 'hourly',
                'number_of_weeks' => '0', // Adjust this if needed
                'wages_per_year' => '0', // Set to the appropriate default if applicable
            ],
            [
                'wage_type' => 'weekly',
                'name' => 'Salary(weekly)',
                'number_of_weeks' => '1',
                'wages_per_year' => '52',
            ],
            [
                'wage_type' => 'bi-weekly',
                'name' => 'Salary(bi-weekly)',
                'number_of_weeks' => '2',
                'wages_per_year' => '24',
            ],
            [
                'wage_type' => 'monthly',
                'name' => 'Salary(monthly)',
                'number_of_weeks' => '4',
                'wages_per_year' => '12',
            ],
            [
                'wage_type' => 'annual',
                'name' => 'Salary(annual)',
                'number_of_weeks' => '52',
                'wages_per_year' => '1',
            ],
        ]);
    }
}
