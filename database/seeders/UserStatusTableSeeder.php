<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the data into the user_status table
        DB::table('user_status')->insert([
            ['id' => 1, 'user_status_name' => 'Active', 'description' => ''],
            ['id' => 2, 'user_status_name' => 'Leave', 'description' => 'Illness/Injury'],
            ['id' => 3, 'user_status_name' => 'Leave', 'desription' => 'Maternity/Parental'],
            ['id' => 4, 'user_status_name' => 'Leave', 'description' => 'Other'],
            ['id' => 5, 'user_status_name' => 'Terminated', 'description' => ''],
        ]);
    }
}
