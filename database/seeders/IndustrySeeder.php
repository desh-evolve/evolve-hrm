<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndustrySeeder extends Seeder
{
    public function run()
    {
        DB::table('industries')->insert([
            [
                'industry_name' => 'Agriculture, Forestry, Fishing and Hunting',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Mining and Oil and Gas Extraction',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Utilities',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Construction',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Manufacturing',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Wholesale Trade',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Retail Trade',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Transportation and Warehousing',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Information and Cultural Industries',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Finance and Insurance',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Real Estate and Rental and Leasing',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Professional, Scientific and Technical Services',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Management of Companies and Enterprises',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Educational Services',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Health Care and Social Assistance',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Arts, Entertainment and Recreation',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Accommodation and Food Services',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Government/Public Administration',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Publishing Industries (except Internet)',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Motion Picture and Sound Recording Industries',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Telecommunications',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Data Processing, Hosting and Related Services',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Administrative and Support Services',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Waste Management and Remediation Services',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
            [
                'industry_name' => 'Other',
                'created_by'    => 0,
                'updated_by'    => 0,
            ],
        ]);
    }
}
