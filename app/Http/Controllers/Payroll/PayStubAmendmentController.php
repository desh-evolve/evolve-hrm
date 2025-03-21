<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Carbon\Carbon;

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
                    'user_ids' => 'nullable|json',
                ]);

                $payStubAmendId = null; // Initialize outside loop for response check.

                if (!empty($request->user_ids)) {
                    $empIds = json_decode($request->user_ids, true);
                    if (is_array($empIds)) {
                        foreach ($empIds as $empId) {
                            $payStubAmendInput = [
                                'user_id' => $empId,
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
                    'user_ids' => 'nullable|json',
                ]);

                $table = 'pay_stub_amendment';
                $idColumn = 'id';
                $payStubAmendId = null; // Initialize outside loop for response check.

                if (!empty($request->user_ids)) {
                    $empIds = json_decode($request->user_ids, true);
                    if (is_array($empIds)) {
                        foreach ($empIds as $empId) {
                            // Check if a record already exists
                            $existingRecord = DB::table($table)
                                ->where('user_id', $empId)
                                ->where('pay_stub_entry_name_id', $request->pay_stub_entry_name_id)
                                ->where('effective_date', $request->effective_date)
                                ->first();

                            if ($existingRecord) {
                                continue; // Skip duplicates
                            }

                            $payStubAmendInput = [
                                'user_id' => $empId,
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
            'emp_employees' => ['emp_employees.user_id', '=', 'pay_stub_amendment.user_id'],
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

        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);
        $pay_stub_entry_accounts = $this->common->commonGetAll('pay_stub_entry_account', '*');
        return response()->json([
            'data' => [
                'users' => $users,
                'pay_stub_entry_accounts' => $pay_stub_entry_accounts,
            ]
            // 'data' => $users,
        ], 200);
    }

    public function getByUserIdAndAuthorizedAndStartDateAndEndDate($user_id, $authorized, $start_date, $end_date){
        if ( $user_id == '' || $authorized == '' || $start_date == '' || $end_date == '') {
			return FALSE;
		}

        $table = 'pay_stub_amendment';
        $fields = 'pay_stub_amendment.*';
        $joinArr = [
           'pay_stub_entry_account' => ['pay_stub_entry_account.id', '=', 'pay_stub_amendment.pay_stub_entry_name_id'] 
        ];
        
        $whereArr = [
            ['pay_stub_amendment.authorized', '=', $authorized],
            ['pay_stub_amendment.effective_date', '>=', '"'.Carbon::parse($start_date)->format('Y-m-d').'"'],
            ['pay_stub_amendment.effective_date', '<=', '"'.Carbon::parse($end_date)->format('Y-m-d').'"'],
            //'pay_stub_amendment.user_id IN '. $user_id,
            ['pay_stub_amendment.user_id', '=', $user_id],
            ['pay_stub_entry_account.status', '!=', '"delete"'],
            
        ];

        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = 'pay_stub_amendment.effective_date asc, pay_stub_amendment.type asc';

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy);
        
        return $res;

        //================================================
        //old system comments
        //================================================
        
		//CalculatePayStub uses this to find PS amendments.
		//Because of percent amounts, make sure we order by effective date FIRST,
		//Then FIXED amounts, then percents.
        
		//Pay period end dates never equal the start start date, so >= and <= are proper.

		//06-Oct-06: Start including YTD_adjustment entries for the new pay stub calculation system.
		//						AND ytd_adjustment = 0
        
		//Make sure we ignore any pay stub amendments that happen to belong to deleted pay stub accounts.
        //================================================
      
    }
}
