<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoundIntervalPunchTypesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'All Punches'],
            ['name' => 'All In (incl. Lunch)'],
            ['name' => 'All Out (incl. Lunch)'],
            ['name' => 'In'],
            ['name' => 'Out'],
            ['name' => 'Lunch - In'],
            ['name' => 'Lunch - Out'],
            ['name' => 'Break - In'],
            ['name' => 'Break - Out'],
            ['name' => 'Lunch Total'],
            ['name' => 'Break Total'],
            ['name' => 'Day Total'],
        ];

        DB::table('round_interval_punch_types')->insert($data);
    }
}
