<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class OvertimePolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view overtime policy', ['only' => ['index', 'getAllOvertimePolicies']]);
        $this->middleware('permission:create overtime policy', ['only' => ['form', 'getOvertimeDropdownData', 'createOvertimePolicy']]);
        $this->middleware('permission:update overtime policy', ['only' => ['form', 'getOvertimeDropdownData', 'updateOvertimePolicy', 'getOvertimePolicyById']]);
        $this->middleware('permission:delete overtime policy', ['only' => ['deleteOvertimePolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.overtime.index');
    }

    public function form()
    {
        return view('policy.overtime.form');
    }

    public function getOvertimeDropdownData(){
        $ot_types = $this->common->commonGetAll('overtime_types', '*');
        $wage_groups = $this->common->commonGetAll('com_wage_groups', ['id', 'wage_group_name AS name']);
        $pay_stubs = $this->common->commonGetAll('pay_stub_entry_account', '*');
        $accrual_policies = $this->common->commonGetAll('accrual_policy', '*');
        return response()->json([
            'data' => [
                'ot_types' => $ot_types,
                'wage_groups' => $wage_groups,
                'pay_stubs' => $pay_stubs,
                'accrual_policies' => $accrual_policies,
            ]
        ], 200);
    }

    public function getAllOvertimePolicies(){
        $fields = ['overtime_policy.id', 'overtime_policy.name', 'trigger_time', 'max_time', 'rate', 'accrual_policy_id', 'accrual_rate', 'overtime_policy.type_id', 'overtime_types.name AS type'];
        $joinArr = [
            'overtime_types' => ['overtime_types.id', '=', 'overtime_policy.type_id']
        ];
        $overtimes = $this->common->commonGetAll('overtime_policy', $fields, $joinArr);
        return response()->json(['data' => $overtimes], 200);
    }

    public function getOvertimePolicyById($id){
        $overtimes = $this->common->commonGetById($id, 'id', 'overtime_policy','*');
        return response()->json(['data' => $overtimes], 200);
    }

    public function deleteOvertimePolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Overtime Policy';
        $table = 'overtime_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createOvertimePolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type_id' => 'required|integer',
                    'trigger_time' => 'required|integer',
                    'max_time' => 'required|integer',
                    'rate' => 'nullable|numeric',
                    'accrual_policy_id' => 'nullable|integer',
                    'accrual_rate' => 'nullable|numeric',
                    'pay_stub_entry_account_id' => 'nullable|integer',
                    'wage_group_id' => 'required|integer'
                ]);

                // Prepare data to be inserted into the overtime_policy table
                $inputArr = [
                    'company_id' => 1, // Replace with dynamic company ID if applicable
                    'name' => $request->name,
                    'type_id' => $request->type_id,
                    'trigger_time' => $request->trigger_time,
                    'max_time' => $request->max_time,
                    'rate' => $request->rate,
                    'accrual_policy_id' => $request->accrual_policy_id,
                    'accrual_rate' => $request->accrual_rate,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id,
                    'wage_group_id' => $request->wage_group_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                // Save data using a common method
                $overtimePolicyId = $this->common->commonSave('overtime_policy', $inputArr);

                if ($overtimePolicyId) {
                    return response()->json(['status' => 'success', 'message' => 'Overtime policy created successfully', 'data' => ['id' => $overtimePolicyId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create overtime policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateOvertimePolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type_id' => 'required|integer',
                    'trigger_time' => 'required|integer',
                    'max_time' => 'required|integer',
                    'rate' => 'nullable|numeric',
                    'accrual_policy_id' => 'nullable|integer',
                    'accrual_rate' => 'nullable|numeric',
                    'pay_stub_entry_account_id' => 'nullable|integer',
                    'wage_group_id' => 'required|integer'
                ]);

                // Prepare data to be updated in the overtime_policy table
                $inputArr = [
                    'name' => $request->name,
                    'type_id' => $request->type_id,
                    'trigger_time' => $request->trigger_time,
                    'max_time' => $request->max_time,
                    'rate' => $request->rate,
                    'accrual_policy_id' => $request->accrual_policy_id,
                    'accrual_rate' => $request->accrual_rate,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id,
                    'wage_group_id' => $request->wage_group_id,
                    'updated_by' => Auth::user()->id,
                ];

                // Use common method to update the policy
                $updatedId = $this->common->commonSave('overtime_policy', $inputArr, $id, 'id');

                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Overtime policy updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update overtime policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

}