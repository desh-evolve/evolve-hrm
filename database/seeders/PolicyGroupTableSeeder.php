<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolicyGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the data into the policy_group table
        DB::table('policy_group')->insert([
            ['company_id' => 1, 'name' => 'PG 1'],
            ['company_id' => 1, 'name' => 'PG 2'],
            ['company_id' => 1, 'name' => 'PG 3'],
        ]);
    }
}
