<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayStubAmendmentController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view pay stub amendment', ['only' => [
            'index',
            'getAllPayStubAmendment',
            'getDropdownList',
            'getPayStubAmendmentById',
        ]]);
        $this->middleware('permission:create pay stub amendment', ['only' => ['createPayStubAmendment']]);
        $this->middleware('permission:update pay stub amendment', ['only' => ['updatePayStubAmendment']]);
        $this->middleware('permission:delete pay stub amendment', ['only' => ['deletePayStubAmendment']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('payroll.pay_stub_amendment.index');
    }
    public function form()
    {
        return view('payroll.pay_stub_amendment.form');
    }

    public function createPayStubAmendment(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate the request
                $request->validate([
                    'pay_stub_entry_name_id' => 'required',
                    'ytd_adjustment' => 'nullable',
                    'type' => 'nullable',
                    'employee_ids' => 'nullable|json',
                ]);

                $payStubAmendId = null; // Initialize outside loop for response check.

                if (!empty($request->employee_ids)) {
                    $empIds = json_decode($request->employee_ids, true);
                    if (is_array($empIds)) {
                        foreach ($empIds as $empId) {
                            $payStubAmendInput = [
                                'employee_id' => $empId,
                                'pay_stub_entry_name_id' => $request->pay_stub_entry_name_id,
                                'effective_date' => $request->effective_date,
                                'rate' => $request->rate,
                                'units' => $request->units,
                                'amount' => $request->amount,
                                'description' => $request->description,
                                'recurring_ps_amendment_id' => $request->recurring_ps_amendment_id,
                                'ytd_adjustment' => $request->ytd_adjustment,
                                'type' => $request->type,
                                'percent_amount' => $request->percent_amount,
                                'percent_amount_entry_name_id' => $request->percent_amount_entry_name_id,
                                'status' => $request->pay_stub_amendment_status,
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ];

                            // Insert into `pay_stub_amendment`
                            $payStubAmendId = $this->common->commonSave('pay_stub_amendment', $payStubAmendInput);
                        }
                    }
                }

                if ($payStubAmendId) {
                    return response()->json(['status' => 'success', 'message' => 'Amendment added successfully', 'data' => ['id' => $payStubAmendId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add Amendment', 'data' => []], 500);
                }
            });
        } catch (\Exception $e) {
            // Log the error for debugging
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function updatePayStubAmendment(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'pay_stub_entry_name_id' => 'required',
                    'ytd_adjustment' => 'nullable',
                    'type' => 'nullable',
                    'employee_ids' => 'nullable|json',
                ]);

                $table = 'pay_stub_amendment';
                $idColumn = 'id';
                $payStubAmendId = null; // Initialize outside loop for response check.

                if (!empty($request->employee_ids)) {
                    $empIds = json_decode($request->employee_ids, true);
                    if (is_array($empIds)) {
                        foreach ($empIds as $empId) {
                            // Check if a record already exists
                            $existingRecord = DB::table($table)
                                ->where('employee_id', $empId)
                                ->where('pay_stub_entry_name_id', $request->pay_stub_entry_name_id)
                                ->where('effective_date', $request->effective_date)
                                ->first();

                            if ($existingRecord) {
                                continue; // Skip duplicates
                            }

                            $payStubAmendInput = [
                                'employee_id' => $empId,
                                'pay_stub_entry_name_id' => $request->pay_stub_entry_name_id,
                                'effective_date' => $request->effective_date,
                                'rate' => $request->rate,
                                'units' => $request->units,
                                'amount' => $request->amount,
                                'description' => $request->description,
                                'recurring_ps_amendment_id' => $request->recurring_ps_amendment_id,
                                'ytd_adjustment' => $request->ytd_adjustment,
                                'type' => $request->type,
                                'percent_amount' => $request->percent_amount,
                                'percent_amount_entry_name_id' => $request->percent_amount_entry_name_id,
                                'status' => $request->pay_stub_amendment_status,
                                'updated_by' => Auth::user()->id,
                            ];

                            // Insert or update record
                            $payStubAmendId = $this->common->commonSave($table, $payStubAmendInput, $id, $idColumn);
                        }
                    }
                }

                if ($payStubAmendId) {
                    return response()->json(['status' => 'success', 'message' => 'Amendment updated successfully', 'data' => ['id' => $payStubAmendId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Amendment', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }


    public function deletePayStubAmendment($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Pay Stub Amendment';
        $table = 'pay_stub_amendment';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllPayStubAmendment()
    {
        $table = 'pay_stub_amendment';
        $fields = ['pay_stub_amendment.*', 'pay_stub_entry_account.name as account_name', 'pay_stub_entry_account.type as account_type', 'pay_stub_amendment.id as id', 'emp_employees.first_name', 'emp_employees.last_name'];

        $joinsArr = [
            'emp_employees' => ['emp_employees.id', '=', 'pay_stub_amendment.employee_id'],
            'pay_stub_entry_account' => ['pay_stub_entry_account.id', '=', 'pay_stub_amendment.pay_stub_entry_name_id'],
        ];
        $pay_stub_account = $this->common->commonGetAll($table, $fields, $joinsArr);
        return response()->json(['data' => $pay_stub_account], 200);
    }

    public function getPayStubAmendmentById($id)
    {
        $idColumn = 'id';
        $table = 'pay_stub_amendment';
        $fields = '*';
        $pay_stub_account = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $pay_stub_account], 200);
    }
    public function getDropdownList()
    {

        $employees = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);
        $pay_stub_entry_accounts = $this->common->commonGetAll('pay_stub_entry_account', '*');
        return response()->json([
            'data' => [
                'employees' => $employees,
                'pay_stub_entry_accounts' => $pay_stub_entry_accounts,
            ]
            // 'data' => $employees,
        ], 200);
    }
}
