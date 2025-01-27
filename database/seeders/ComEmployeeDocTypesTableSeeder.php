<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComEmployeeDocTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the data into the policy_group table
        DB::table('com_employee_doc_types')->insert([
            ['id' => 1, 'name' => 'GS Certificate'],
            ['id' => 2, 'name' => 'Doc 2'],
            ['id' => 3, 'name' => 'Doc 3'],
            ['id' => 4, 'name' => 'Doc 4'],
        ]);
    }
}