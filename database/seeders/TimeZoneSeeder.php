<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeZones = [
            ['name' => 'Greenwich Mean Time', 'abbreviation' => 'GMT', 'utc_offset' => '+00:00', 'value' => 'gmt','status'=> 'Active'],
            ['name' => 'Eastern Standard Time', 'abbreviation' => 'EST', 'utc_offset' => '-05:00', 'value' => 'est','status'=> 'Active'],
            ['name' => 'Central Standard Time', 'abbreviation' => 'CST', 'utc_offset' => '-06:00', 'value' => 'cst','status'=> 'Active'],
            ['name' => 'Mountain Standard Time', 'abbreviation' => 'MST', 'utc_offset' => '-07:00', 'value' => 'mst','status'=> 'Active'],
            ['name' => 'Pacific Standard Time', 'abbreviation' => 'PST', 'utc_offset' => '-08:00', 'value' => 'pst','status'=> 'Active'],
            ['name' => 'Indian Standard Time', 'abbreviation' => 'IST', 'utc_offset' => '+05:30', 'value' => 'ist','status'=> 'Active'],
            ['name' => 'Australian Eastern Standard Time', 'abbreviation' => 'AEST', 'utc_offset' => '+10:00', 'value' => 'aest','status'=> 'Active'],
            ['name' => 'Central European Time', 'abbreviation' => 'CET', 'utc_offset' => '+01:00', 'value' => 'cet','status'=> 'Active'],
            ['name' => 'China Standard Time', 'abbreviation' => 'CST', 'utc_offset' => '+08:00', 'value' => 'cst_china','status'=> 'Active'],
            ['name' => 'Asia/Colombo', 'abbreviation' => 'IST', 'utc_offset' => '+05:30', 'value' => 'asia_colombo','status'=> 'Active'], // Added Asia/Colombo
            ['name' => 'Japan Standard Time', 'abbreviation' => 'JST', 'utc_offset' => '+09:00', 'value' => 'jst','status'=> 'Active'],
            ['name' => 'Alaska Standard Time', 'abbreviation' => 'AKST', 'utc_offset' => '-09:00', 'value' => 'akst','status'=> 'Active'],
            ['name' => 'Hawaii-Aleutian Standard Time', 'abbreviation' => 'HAST', 'utc_offset' => '-10:00', 'value' => 'hast','status'=> 'Active'],
            ['name' => 'Singapore Standard Time', 'abbreviation' => 'SGT', 'utc_offset' => '+08:00', 'value' => 'sgt','status'=> 'Active'],
            ['name' => 'British Summer Time', 'abbreviation' => 'BST', 'utc_offset' => '+01:00', 'value' => 'bst','status'=> 'Active'],
            ['name' => 'Eastern European Time', 'abbreviation' => 'EET', 'utc_offset' => '+02:00', 'value' => 'eet','status'=> 'Active'],
            ['name' => 'Brazil Standard Time', 'abbreviation' => 'BRT', 'utc_offset' => '-03:00', 'value' => 'brt','status'=> 'Active'],
            ['name' => 'Moscow Standard Time', 'abbreviation' => 'MSK', 'utc_offset' => '+03:00', 'value' => 'msk','status'=> 'Active'],
            ['name' => 'New Zealand Standard Time', 'abbreviation' => 'NZST', 'utc_offset' => '+12:00', 'value' => 'nzst','status'=> 'Active'],
            ['name' => 'Atlantic Standard Time', 'abbreviation' => 'AST', 'utc_offset' => '-04:00', 'value' => 'ast','status'=> 'Active'],
        ];

        DB::table('time_zones')->insert($timeZones);
    }
}
