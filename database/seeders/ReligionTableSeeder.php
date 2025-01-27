<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the data into the religion table
        DB::table('religion')->insert([
            ['id' => 1, 'name' => 'Buddhism'],
            ['id' => 2, 'name' => 'Christian'],
            ['id' => 3, 'name' => 'Islam'],
            ['id' => 4, 'name' => 'Hindu'],
            ['id' => 5, 'name' => 'Other'],
        ]);
    }
}
