<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('com_companies')->insert([
            [
                'company_name' => 'Evolve Technologies Pvt Ltd',
                'company_short_name' => 'Evolve',
                'industry_id' => 1,
                'business_reg_no' => 'BRN12345',
                'address_1' => '1234 Innovation St.',
                'address_2' => 'Suite 100',
                'city_id' => 2,
                'province_id' => 1,
                'country_id' => 1,
                'postal_code' => '10001',
                'contact_1' => '1234567890',
                'contact_2' => '0987654321',
                'email' => 'info@techinno.com',
                'epf_reg_no' => 'EPF123456',
                'tin_no' => 'TIN654321',
                'admin_contact_id' => 1,
                'billing_contact_id' => 2,
                'primary_contact_id' => 1,
                'logo' => 'logos/techinno.png',
                'logo_small' => 'logos/techinno_small.png',
                'website' => 'https://www.techinno.com',
                'status' => 'active',
            ],
            // Add more companies here if needed
        ]);
    }
}
