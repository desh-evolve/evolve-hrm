<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayPeriodScheduleController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view pay period schedule', ['only' => ['index', 'getAllPayPeriodSchedules']]);
        $this->middleware('permission:create pay period schedule', ['only' => ['form', 'getPayPeriodScheduleDropdownData', 'createPayPeriodSchedule']]);
        $this->middleware('permission:update pay period schedule', ['only' => ['form', 'getPayPeriodScheduleDropdownData', 'getPayPeriodScheduleById', 'updatePayPeriodSchedule']]);
        $this->middleware('permission:delete pay period schedule', ['only' => ['deletePayPeriodSchedule']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('payroll.pay_period_schedule.index');
    }

    public function form()
    {
        return view('payroll.pay_period_schedule.form');
    }

    public function getPayPeriodScheduleDropdownData()
    {
        $time_zoness = $this->common->commonGetAll('time_zones', ['value as id', 'name']);
        $employees = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        //type => create table
        $type = [
            [ 'id' => 1, 'name' => 'Manual', 'value' => 'manual'],
            [ 'id' => 2, 'name' => 'Weekly(52/year)', 'value' => 'weekly'],
            [ 'id' => 3, 'name' => 'Bi-Weekly (26/year)', 'value' => 'bi-weekly'],
            [ 'id' => 4, 'name' => 'Semi-Monthly (24/year)', 'value' => 'semi-monthly'],
            [ 'id' => 5, 'name' => 'Monthly (12/year)', 'value' => 'monthly'],
        ];
        //Assign Shifts To => create table
        $assign_shift_to = [
            [ 'id' => 1, 'name' => 'Day They Start On', 'value' => 'day-they-start-on'],
            [ 'id' => 2, 'name' => 'Day They End On', 'value' => 'day-they-end-on'],
            [ 'id' => 3, 'name' => 'Day w/Most Time Worked', 'value' => 'day-w/most-time-worked'],
            [ 'id' => 4, 'name' => 'Each Day (Split at Midnight)', 'value' => 'day-each'],
        ];
        //Timesheet verify on => create table
        $timesheet_verify_on = [
            [ 'id' => 1, 'name' => 'Disabled', 'value' => 'disabled'],
            [ 'id' => 2, 'name' => 'Employee Only', 'value' => 'employee-only'],
            [ 'id' => 3, 'name' => 'Superior Only', 'value' => 'superior-only'],
            [ 'id' => 4, 'name' => 'Employee & Superior', 'value' => 'employee-superior'],
        ];
  
        return response()->json([
            'data' => [
                'time_zoness' => $time_zoness,
                'employees' => $employees,
                'type' => $type, 
                'assign_shift_to' => $assign_shift_to, 
                'timesheet_verify_on' => $timesheet_verify_on, 
            ]
        ], 200);
    }

    public function getAllPayPeriodSchedules()
    {
        $pg = $this->common->commonGetAll('pay_period_schedule', '*');
        return response()->json(['data' => $pg], 200);
    }

    public function getPayPeriodScheduleById($id)
    {
        $connections = [
            'pay_period_schedule_employee' => [
                'con_fields' => ['employee_id'],  // Fields to select from connected table
                'con_where' => ['pay_period_schedule_employee.pay_period_schedule_id' => 'id'],  // Link to the main table 
                'con_joins' => [],
                'con_name' => 'employees',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];
        $joinsArr = [''];
        $fields = ['pay_period_schedule.*', 'pay_period_schedule.id as id', 'com_wage_groups.wage_group_name' , 'com_wage_type.name as wage_type_name', 'com_wage_type.wage_type'];
        // $joinsArr = [
        //     'com_wage_groups' => ['com_wage_groups.id', '=', 'pay_period_schedule.wage_group_id'],
        //     'com_wage_type' => ['com_wage_type.id', '=', 'pay_period_schedule.wage_type_id']
        // ];
        $pg = $this->common->commonGetById($id, 'id', 'pay_period_schedule',$fields, $joinsArr, [], false, $connections);
        return response()->json(['data' => $pg], 200);
    }

    // $id, $idColumn, $table, $fields, $joinsArr = [], $whereArr = [], $exceptDel = false, $connections = [], $groupBy = null, $orderBy = null

    public function deletePayPeriodSchedule($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Pay Period Schedule';
        $table = 'pay_period_schedule';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    /**
     * Create a new pay period schedule with associated policies.
     */
    public function createPayPeriodSchedule(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'timesheet_verify_type' => 'required|string',
                    'employee_ids' => 'nullable|json',
                ]);

                $payPeriodScheduleInput = [
                    'company_id' => 1, // Replace with dynamic company ID
                    'name' => $request->name,
                    'description' => $request->description,
                    'start_week_day' => $request->start_week_day,
                    'time_zone' => $request->time_zone,
                    'new_day_trigger_time' => $request->new_day_trigger_time,
                    'maximum_shift_time' => $request->maximum_shift_time,
                    'shift_assigned_day' => $request->shift_assigned_day,
                    'timesheet_verify_type' => $request->timesheet_verify_type,
                    'type' => $request->type,
                    'start_day_of_week' => $request->start_day_of_week,
                    'transaction_date' => $request->transaction_date,
                    'transaction_date_bd' => $request->transaction_date_bd,
                    'anchor_date' => $request->anchor_date,

                    'timesheet_verify_before_end_date' => $request->timesheet_verify_before_end_date,
                    'timesheet_verify_before_transaction_date' => $request->timesheet_verify_before_transaction_date,

                    'primary_day_of_month' => $request->primary_day_of_month,
                    'primary_transaction_day_of_month' => $request->primary_transaction_day_of_month,
                    
                    'secondary_day_of_month' => $request->secondary_day_of_month,
                    'secondary_transaction_day_of_month' => $request->secondary_transaction_day_of_month,
                    'transaction_date_bd' => $request->transaction_date_bd,

                    
                    'status' => $request->pay_period_schedule_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                // Insert into `pay_period_schedule`
                $payPeriodScheduleId = $this->common->commonSave('pay_period_schedule', $payPeriodScheduleInput);

                if (!$payPeriodScheduleId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create pay period schedule'], 500);
                }

                // Save associated policies
                // $this->savePolicies($payPeriodScheduleId, $request);
                $this->savePayPeriodScheduleEmployees($payPeriodScheduleId, $request);

                return response()->json(['status' => 'success', 'message' => 'Policy group created successfully', 'data' => ['id' => $payPeriodScheduleId]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing pay period schedule with associated policies.
     */
    public function updatePayPeriodSchedule(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'timesheet_verify_type' => 'required|string',
                    'employee_ids' => 'nullable|json',
                ]);

                $payPeriodScheduleInput = [
                    'company_id' => 1, // Replace with dynamic company ID
                    'name' => $request->name,
                    'description' => $request->description,
                    'start_week_day' => $request->start_week_day,
                    'time_zone' => $request->time_zone,
                    'new_day_trigger_time' => $request->new_day_trigger_time,
                    'maximum_shift_time' => $request->maximum_shift_time,
                    'shift_assigned_day' => $request->shift_assigned_day,
                    'timesheet_verify_type' => $request->timesheet_verify_type,
                    'type' => $request->type,
                    'start_day_of_week' => $request->start_day_of_week,
                    'transaction_date' => $request->transaction_date,
                    'transaction_date_bd' => $request->transaction_date_bd,
                    'anchor_date' => $request->anchor_date,
                    
                    'timesheet_verify_before_end_date' => $request->timesheet_verify_before_end_date,
                    'timesheet_verify_before_transaction_date' => $request->timesheet_verify_before_transaction_date,

                    'primary_day_of_month' => $request->primary_day_of_month,
                    'primary_transaction_day_of_month' => $request->primary_transaction_day_of_month,
                    
                    'secondary_day_of_month' => $request->secondary_day_of_month,
                    'secondary_transaction_day_of_month' => $request->secondary_transaction_day_of_month,
                    'transaction_date_bd' => $request->transaction_date_bd,

                    
                    'status' => $request->pay_period_schedule_status,

                    'updated_by' => Auth::user()->id,
                ];

                // Update the `pay_period_schedule` table
                $updated = $this->common->commonSave('pay_period_schedule', $payPeriodScheduleInput, $id, 'id');

                if (!$updated) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update pay period schedule'], 500);
                }
                
                $this->savePayPeriodScheduleEmployees($id, $request);

                return response()->json(['status' => 'success', 'message' => 'Policy group updated successfully', 'data' => ['id' => $id]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Save associated policies for a pay period schedule.
     *
     * @param int $payPeriodScheduleId
     * @param Request $request
     */

    // private function savePayPeriodScheduleEmployees($payPeriodScheduleId, $request)
    // {
    //     if (!empty($request->employee_ids)) {
    //         $empIds = json_decode($request->employee_ids, true);
    //         if (is_array($empIds)) {
    //             foreach ($empIds as $empId) {

    //                 DB::table('pay_period_schedule_employee')
    //                 ->where('pay_period_schedule_id', $payPeriodScheduleId)
    //                 ->where('employee_id', $empId)
    //                 ->delete();

    //                 DB::table('pay_period_schedule_employee')->insert([
    //                     'pay_period_schedule_id' => $payPeriodScheduleId,
    //                     'employee_id' => $empId,
    //                 ]);
    //             }
    //         }
    //     }
    // }

    private function savePayPeriodScheduleEmployees($payPeriodScheduleId, $request)
    {
        if (!empty($request->employee_ids)) {
            $empIds = json_decode($request->employee_ids, true);

            if (is_array($empIds)) {
                // Delete all existing employees for this pay period schedule
                DB::table('pay_period_schedule_employee')
                    ->where('pay_period_schedule_id', $payPeriodScheduleId)
                    ->whereIn('employee_id', $empIds)
                    ->delete();

                // Prepare bulk insert data
                $insertData = array_map(function ($empId) use ($payPeriodScheduleId) {
                    return [
                        'pay_period_schedule_id' => $payPeriodScheduleId,
                        'employee_id' => $empId,
                    ];
                }, $empIds);

                // Insert all employees in a single query
                DB::table('pay_period_schedule_employee')->insert($insertData);
            }
        }
    }
}
