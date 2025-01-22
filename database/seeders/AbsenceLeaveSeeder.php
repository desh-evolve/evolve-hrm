<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsenceLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('absence_leave')->insert([
            [
                'name' => 'Full Day Leave',
                'short_code' => 'FD',
                'time_seconds' => 28800, 
                'related_leave_id' => 1,
                'related_leave_unit' => 1, 
            ],
            [
                'name' => 'Half Day Leave',
                'short_code' => 'HD',
                'time_seconds' => 14400,
                'related_leave_id' => 1,
                'related_leave_unit' => 2,
            ],
            [
                'name' => 'Short Leave',
                'short_code' => 'SL',
                'time_seconds' => 5400,
                'related_leave_id' => 1,
                'related_leave_unit' => 3,
            ]
        ]);
    }
}
