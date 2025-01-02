<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class CompanyDeductionController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view company deduction', ['only' => ['index', 'getAllCompanyDeduction']]);
        $this->middleware('permission:create company deduction', ['only' => ['form', 'getCompanyDeductionDropdownData', 'createCompanyDeduction']]);
        $this->middleware('permission:update company deduction', ['only' => ['form', 'getCompanyDeductionDropdownData', 'getCompanyDeductionById', 'updateCompanyDeduction']]);
        $this->middleware('permission:delete company deduction', ['only' => ['deleteCompanyDeduction']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('payroll.company_deduction.index');
    }

    public function form()
    {
        return view('payroll.company_deduction.form');
    }

    public function getCompanyDeductionDropdownData()
    {
        $pay_stub_entry_accounts = DB::table('pay_stub_entry_account')
            ->select(
                'id',
                DB::raw("CONCAT(UPPER(LEFT(`type`, 1)), LOWER(SUBSTRING(`type`, 2)), ' - ', `name`) AS name")
            )
            ->where('status', 'active')
            ->get();

        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        //type => create table
        $type = [
            ['id' => 1, 'name' => 'Tax', 'value' => 'tax'],
            ['id' => 2, 'name' => 'Deduction', 'value' => 'deduction'],
            ['id' => 3, 'name' => 'Other', 'value' => 'other'],
        ];
        //Assign Shifts To => create table
        $length_of_service = [
            ['id' => 1, 'name' => 'Day(s)', 'value' => 'day'],
            ['id' => 2, 'name' => 'Week(s)', 'value' => 'week'],
            ['id' => 3, 'name' => 'Month(s)', 'value' => 'month'],
            ['id' => 4, 'name' => 'Year(s)', 'value' => 'year'],
            ['id' => 5, 'name' => 'Hour(s)', 'value' => 'hour'],
        ];
        //Timesheet verify on => create table
        $basis_of_employment = [
            ['id' => 1, 'name' => 'Contract', 'value' => 'contract'],
            ['id' => 2, 'name' => 'Permanent', 'value' => 'permanent'],
            ['id' => 3, 'name' => 'All', 'value' => 'all'],
        ];
        //Timesheet verify on => create table
        $calculation_list = [

            ['id' => 1, 'name' => 'Percent', 'value' => 'percent'],
            ['id' => 2, 'name' => 'Fixed Amount', 'value' => 'fixed_amount'],
            ['id' => 3, 'name' => 'Fixed Amount (Range Bracket)', 'value' => 'fixed_amount_range_bracket'],
            ['id' => 4, 'name' => 'Advanced Percent', 'value' => 'advanced_percent'],
            ['id' => 5, 'name' => 'Advanced Percent (Range Bracket)', 'value' => 'advanced_percent_range_bracket'],
        ];

        //Timesheet verify on => create table
        $amount_type_list = [

            ['id' => 1, 'name' => 'Amount', 'value' => 'amount'],
            ['id' => 2, 'name' => 'Units/Hours', 'value' => 'units_hours'],
            ['id' => 3, 'name' => 'YTD Amount', 'value' => 'ytd_amount'],
            ['id' => 4, 'name' => 'YTD Units/Hours', 'value' => 'ytd_units_hours'],
        ];

        return response()->json([
            'data' => [
                'pay_stub_entry_accounts' => $pay_stub_entry_accounts,
                'users' => $users,
                'type' => $type,
                'length_of_service' => $length_of_service,
                'basis_of_employment' => $basis_of_employment,
                'calculation_list' => $calculation_list,
                'amount_type_list' => $amount_type_list,
            ]
        ], 200);
    }

    public function getAllCompanyDeduction()
    {
        $pg = $this->common->commonGetAll('pay_company_deduction', '*');
        return response()->json(['data' => $pg], 200);
    }

    public function getCompanyDeductionById($id)
    {

        $connections = [
            'pay_user_deduction' => [
                'con_fields' => ['user_id'],  // Fields to select from connected table
                'con_where' => ['pay_user_deduction.company_deduction_id' => 'id'],  // Link to the main table 
                'con_joins' => [],
                'con_name' => 'users',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];
        $pg = $this->common->commonGetById($id, 'id', 'pay_company_deduction', '*', [], [], false, $connections);
        return response()->json(['data' => $pg], 200);
    }

    // $id, $idColumn, $table, $fields, $joinsArr = [], $whereArr = [], $exceptDel = false, $connections = [], $groupBy = null, $orderBy = null

    public function deleteCompanyDeduction($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Pay Period Schedule';
        $table = 'pay_company_deduction';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    /**
     * Create a new pay period schedule with associated policies.
     */
    public function createCompanyDeduction(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    // 'calculation_type' => 'required',
                    'calculation_order' => 'required|string',
                    // 'pay_stub_entry_account_id' => 'required',
                    // 'include_account_amount_type' => 'required',
                    // 'exclude_account_amount_type' => 'required',
                    // 'basis_of_employment' => 'required',
                    // 'include_pay_stub_entry_account_ids' => 'nullable|json',
                    // 'exclude_pay_stub_entry_account_ids' => 'nullable|json',
                    // 'user_ids' => 'nullable|json',
                ]);

                dd($request->all());
                $payPeriodScheduleInput = [
                    'company_id' => 1, // Replace with dynamic company ID
                    'type' => $request->type,
                    'name' => $request->name,
                    'calculation_type' => $request->calculation_type,
                    'calculation_order' => $request->calculation_order,
                    'user_value1' => $request->user_value1,
                    'user_value2' => $request->user_value2,
                    'user_value3' => $request->user_value3,
                    'user_value4' => $request->user_value4,
                    'user_value5' => $request->user_value5,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'minimum_length_of_service' => $request->minimum_length_of_service,
                    'minimum_length_of_service_unit' => $request->minimum_length_of_service_unit,
                    'minimum_length_of_service_days' => $request->minimum_length_of_service_days,
                    'maximum_length_of_service' => $request->maximum_length_of_service,
                    'maximum_length_of_service_unit' => $request->maximum_length_of_service_unit,
                    'maximum_length_of_service_days' => $request->maximum_length_of_service_days,
                    'include_account_amount_type' => $request->include_account_amount_type,
                    'exclude_account_amount_type' => $request->exclude_account_amount_type,
                    'minimum_user_age' => $request->minimum_user_age,
                    'maximum_user_age' => $request->maximum_user_age,
                    'basis_of_employment' => $request->basis_of_employment,

                    'status' => $request->company_deduction_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                // dd($payPeriodScheduleInput);

                // Insert into `pay_period_schedule`
                $companyDeductionId = $this->common->commonSave('pay_company_deduction', $payPeriodScheduleInput);

                if (!$companyDeductionId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create Company Deduction'], 500);
                }

                // Save associated policies
                $this->saveCompanyDeductionPayStubEntryAccount($companyDeductionId, $request);
                $this->saveCompanyDeductionEmployees($companyDeductionId, $request);

                return response()->json(['status' => 'success', 'message' => 'Company Deduction created successfully', 'data' => ['id' => $companyDeductionId]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing pay period schedule with associated policies.
     */
    public function updateCompanyDeduction(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'calculation_type' => 'required|string',
                    'calculation_order' => 'required|string',
                    'pay_stub_entry_account_id' => 'required',
                    'include_account_amount_type' => 'required',
                    'exclude_account_amount_type' => 'required',
                    'basis_of_employment' => 'required',
                    'include_pay_stub_entry_account_ids' => 'nullable|json',
                    'exclude_pay_stub_entry_account_ids' => 'nullable|json',
                    'user_ids' => 'nullable|json',
                ]);

                $payPeriodScheduleInput = [
                    'company_id' => 1, // Replace with dynamic company ID
                    'type' => $request->type,
                    'name' => $request->name,
                    'calculation_type' => $request->calculation_type,
                    'calculation_order' => $request->calculation_order,
                    'user_value1' => $request->user_value1,
                    'user_value2' => $request->user_value2,
                    'user_value3' => $request->user_value3,
                    'user_value4' => $request->user_value4,
                    'user_value5' => $request->user_value5,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'minimum_length_of_service' => $request->minimum_length_of_service,
                    'minimum_length_of_service_unit' => $request->minimum_length_of_service_unit,
                    'minimum_length_of_service_days' => $request->minimum_length_of_service_days,
                    'maximum_length_of_service' => $request->maximum_length_of_service,
                    'maximum_length_of_service_unit' => $request->maximum_length_of_service_unit,
                    'maximum_length_of_service_days' => $request->maximum_length_of_service_days,
                    'include_account_amount_type' => $request->include_account_amount_type,
                    'exclude_account_amount_type' => $request->exclude_account_amount_type,
                    'minimum_user_age' => $request->minimum_user_age,
                    'maximum_user_age' => $request->maximum_user_age,
                    'basis_of_employment' => $request->basis_of_employment,

                    'status' => $request->company_deduction_status,
                    'updated_by' => Auth::user()->id,
                ];

                // Update the `pay_period_schedule` table
                $updated = $this->common->commonSave('pay_company_deduction', $payPeriodScheduleInput, $id, 'id');

                if (!$updated) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update pay period schedule'], 500);
                }

                $this->saveCompanyDeductionPayStubEntryAccount($id, $request);
                $this->saveCompanyDeductionEmployees($id, $request);

                return response()->json(['status' => 'success', 'message' => 'Company Deduction updated successfully', 'data' => ['id' => $id]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Save associated policies for a pay period schedule.
     *
     * @param int $companyDeductionId
     * @param Request $request
     */


    private function saveCompanyDeductionEmployees($companyDeductionId, $request)
    {
        if (!empty($request->user_ids)) {
            $empIds = json_decode($request->user_ids, true);

            if (is_array($empIds)) {
                // Delete all existing users for this pay period schedule
                DB::table('pay_user_deduction')
                    ->where('company_deduction_id', $companyDeductionId)
                    ->whereIn('user_id', $empIds)
                    ->delete();

                // Prepare bulk insert data
                $insertData = array_map(function ($empId) use ($companyDeductionId) {

                    $user_value1 = $request->user_value1 ?? 0;
                    $user_value2 = $request->user_value2 ?? 0;
                    $user_value3 = $request->user_value3 ?? 0;
                    $user_value4 = $request->user_value4 ?? 0;
                    $user_value5 = $request->user_value5 ?? 0;
                    $status = $request->pay_period_schedule_status ?? 'active';
                    return [
                        'company_deduction_id' => $companyDeductionId,
                        'user_id' => $empId,
                        'user_value1' => $user_value1,
                        'user_value2' => $user_value2,
                        'user_value3' => $user_value3,
                        'user_value4' => $user_value4,
                        'user_value5' => $user_value5,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $empIds);

                // Insert all users in a single query
                DB::table('pay_user_deduction')->insert($insertData);
            }
        }
    }
    private function saveCompanyDeductionPayStubEntryAccount($companyDeductionId, $request)
    {
        // exclude_pay_stub_entry_account_ids
        if (!empty($request->exclude_pay_stub_entry_account_ids)) {
            $excludeAccIds = json_decode($request->exclude_pay_stub_entry_account_ids, true);

            if (is_array($excludeAccIds)) {
                // Delete existing records
                DB::table('pay_company_deduction_pay_stub_entry_account')
                    ->where('company_deduction_id', $companyDeductionId)
                    ->whereIn('pay_stub_entry_account_id', $excludeAccIds)
                    ->delete();

                // Prepare bulk insert data
                $insertDataIn = array_map(function ($accId) use ($companyDeductionId, $request) {
                    $exclude_account_amount_type = $request->exclude_account_amount_type ?? 0;
                    $status = $request->pay_period_schedule_status ?? 'active';
                    return [
                        'company_deduction_id' => $companyDeductionId,
                        'pay_stub_entry_account_id' => $accId,
                        'type' => $exclude_account_amount_type,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $excludeAccIds);

                // Insert data
                DB::table('pay_company_deduction_pay_stub_entry_account')->insert($insertDataIn);
            }
        }

        // include_pay_stub_entry_account_ids
        if (!empty($request->include_pay_stub_entry_account_ids)) {
            $includeAccIds = json_decode($request->include_pay_stub_entry_account_ids, true);

            if (is_array($includeAccIds)) {
                // Delete existing records
                DB::table('pay_company_deduction_pay_stub_entry_account')
                    ->where('company_deduction_id', $companyDeductionId)
                    ->whereIn('pay_stub_entry_account_id', $includeAccIds)
                    ->delete();

                // Prepare bulk insert data
                $insertDataEx = array_map(function ($accId) use ($companyDeductionId, $request) {
                    $include_account_amount_type = $request->include_account_amount_type ?? 0;
                    $status = $request->pay_period_schedule_status ?? 'active';
                    return [
                        'company_deduction_id' => $companyDeductionId,
                        'pay_stub_entry_account_id' => $accId,
                        'type' => $include_account_amount_type,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $includeAccIds);

                // Insert data
                DB::table('pay_company_deduction_pay_stub_entry_account')->insert($insertDataEx);
            }
        }
    }
}
