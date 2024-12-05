<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('message_types')->insert([
            [
                'id' => 1,
                'name' => 'Email',
            ],
            [
                'id' => 2,
                'name' => 'punch adjustment',
            ],
            [
                'id' => 3,
                'name' => 'missed punch',
            ],
            [
                'id' => 4,
                'name' => 'schedule adjustment',
            ],
            [
                'id' => 5,
                'name' => 'absence',
            ],
            [
                'id' => 6,
                'name' => 'other',
            ],

        ]);
    }
}
