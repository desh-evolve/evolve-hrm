<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OverTimePolicyTypesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Daily'],
            ['name' => 'Weekly'],
            ['name' => 'Bi-Weekly'],
            ['name' => 'Sunday'],
            ['name' => 'Monday'],
            ['name' => 'Tuesday'],
            ['name' => 'Wednesday'],
            ['name' => 'Thursday'],
            ['name' => 'Friday'],
            ['name' => 'Saturday'],
            ['name' => '2 Or More Days Consecutively Worked'],
            ['name' => '3 Or More Days Consecutively Worked'],
            ['name' => '4 Or More Days Consecutively Worked'],
            ['name' => '5 Or More Days Consecutively Worked'],
            ['name' => '6 Or More Days Consecutively Worked'],
            ['name' => '7 Or More Days Consecutively Worked'],
            ['name' => 'Poya Holiday'],
            ['name' => 'Statutory Holiday'],
            ['name' => 'Over Schedule (Daily) / No Schedule'],
            ['name' => 'Over Schedule (Weekly) / No Schedule'],
        ];

        DB::table('overtime_types')->insert($data);
    }
}
