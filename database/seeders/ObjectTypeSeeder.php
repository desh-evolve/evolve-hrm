<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert the data into the object_type table
        DB::table('object_type')->insert([
            ['type' => 'request', 'name' => 'Request: Missed Punch'],
            ['type' => 'request', 'name' => 'Request: Time Adjustment'],
            ['type' => 'request', 'name' => 'Request: Absence (incl. Vacation)'],
            ['type' => 'request', 'name' => 'Request: Schedule Adjustment'],
            ['type' => 'request', 'name' => 'Request: Other'],
            ['type' => 'other', 'name' => 'Exception'],
            ['type' => 'other', 'name' => 'TimeSheet'],
            ['type' => 'other', 'name' => 'Permission'],
            ['type' => 'email', 'name' => 'Email'],
        ]);
    }
}
