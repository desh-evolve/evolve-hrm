<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use DB;

use Illuminate\Support\Facades\DB;

class EmpEmployeesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('emp_employees')->insert([
            [
                'user_id' => 1,
                'title' => 'Mr',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'full_name' => 'John Doe',
                'name_with_initials' => 'J. Doe',
                'address_1' => '123 Main St',
                'address_2' => 'Apt 4',
                'address_3' => 'District 9',
                'nic' => '123456789V',
                'country_id' => 1,
                'province_id' => 1,
                'city_id' => 1,
                'postal_code' => '10000',
                'contact_1' => '0771234567',
                'contact_2' => '0711234567',
                'work_contact' => '0111234567',
                'home_contact' => '0117654321',
                'immediate_contact_person' => 'Jane Doe',
                'immediate_contact_no' => '0777654321',
                'personal_email' => 'john.doe@example.com',
                'work_email' => 'john.doe@company.com',
                'epf_reg_no' => 'EPF001234',
                'religion' => 1,
                'dob' => '1990-01-01',
                'gender' => 'Male',
                'marital_status' => 'Single',
                'employee_image' => 'john_doe.jpg',
                'punch_machine_user_id' => 1001,
                'designation_id' => 1,
                'employee_group_id' => 1,
                'policy_group_id' => 1,
                'appointment_date' => '2020-01-01',
                'appointment_note' => 'Permanent staff',
                'terminated_date' => null,
                'terminated_note' => null,
                'employment_type_id' => 1,
                'employment_time' => 40,
                'confirmed_date' => '2020-06-01',
                'resigned_date' => null,
                'retirement_date' => null,
                'currency_id' => 1,
                'pay_period_id' => 1,
                'role_id' => 1,
                'bond_period' => '2 years',
                'employee_status' => 1,
            ],
            // Add more sample data if needed
        ]);
    }
}
