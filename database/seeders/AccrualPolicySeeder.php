<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccrualPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accrual_policy')->insert([
            [
                'id' => 1,
                'company_id' => 1,
                'name' => 'Annual Leave Accrual Policy',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 0,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 0,
                'status' => 'delete'
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'name' => 'Casual Leave Accrual Policy',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 1,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 30,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 0,
                'status' => 'active'
            ],
            [
                'id' => 3,
                'company_id' => 1,
                'name' => 'Duty Leave',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 1,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 0,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
            [
                'id' => 4,
                'company_id' => 1,
                'name' => 'Medical Leave Accrual Policy',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 0,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 0,
                'status' => 'active'
            ],
            [
                'id' => 5,
                'company_id' => 1,
                'name' => 'Annual Leave Accrual Policy',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'monthly',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 0,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 0,
                'status' => 'delete'
            ],
            [
                'id' => 6,
                'company_id' => 1,
                'name' => 'Annual Leave Accrual Policy',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 1,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 0,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'delete'
            ],
            [
                'id' => 7,
                'company_id' => 1,
                'name' => 'Annual Leave Accrual Policy',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 1,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 365,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 0,
                'status' => 'active'
            ],
            [
                'id' => 8,
                'company_id' => 1,
                'name' => 'Short  Leave',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'pay_period',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 1,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 1,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
            [
                'id' => 9,
                'company_id' => 1,
                'name' => 'Maternity leave',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 1,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
            [
                'id' => 10,
                'company_id' => 1,
                'name' => 'Paternity Leave',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 1,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 30,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
            [
                'id' => 11,
                'company_id' => 1,
                'name' => 'Lieu Leave',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 30,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
            [
                'id' => 12,
                'company_id' => 1,
                'name' => 'Due to Lieu Leave',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'annually',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 30,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
            [
                'id' => 13,
                'company_id' => 1,
                'name' => 'No Pay',
                'type' => 'standard',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'pay_period',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 0,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
            [
                'id' => 14,
                'company_id' => 1,
                'name' => 'Director approved leave',
                'type' => 'calendar_based',
                'minimum_time' => null,
                'maximum_time' => null,
                'apply_frequency' => 'pay_period',
                'apply_frequency_month' => 1,
                'apply_frequency_day_of_month' => 1,
                'apply_frequency_day_of_week' => 0,
                'milestone_rollover_hire_date' => 0,
                'milestone_rollover_month' => 1,
                'milestone_rollover_day_of_month' => 1,
                'minimum_employed_days' => 0,
                'minimum_employed_days_catchup' => null,
                'enable_pay_stub_balance_display' => 0,
                'apply_frequency_hire_date' => 1,
                'status' => 'active'
            ],
        ]);
    }
}
