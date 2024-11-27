<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AbsencePolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view absence policy', ['only' => ['index', 'getAllAbsencePolicies']]);
        $this->middleware('permission:create absence policy', ['only' => ['form', 'getAbsenceDropdownData', 'createAbsencePolicy']]);
        $this->middleware('permission:update absence policy', ['only' => ['form', 'getAbsenceDropdownData', 'updateAbsencePolicy', 'getAbsencePolicyById']]);
        $this->middleware('permission:delete absence policy', ['only' => ['deleteAbsencePolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.absence.index');
    }

    public function form()
    {
        return view('policy.absence.form');
    }

    public function getAbsenceDropdownData(){
        $wage_groups = $this->common->commonGetAll('com_wage_groups', ['id', 'wage_group_name AS name']);
        $pay_stubs = $this->common->commonGetAll('pay_stub_entry_account', '*');
        $accrual_policies = $this->common->commonGetAll('accrual_policy', '*');
        return response()->json([
            'data' => [
                'wage_groups' => $wage_groups,
                'pay_stubs' => $pay_stubs,
                'accrual_policies' => $accrual_policies,
            ]
        ], 200);
    }

    public function getAllAbsencePolicies(){
        $absences = $this->common->commonGetAll('absence_policy', '*');
        return response()->json(['data' => $absences], 200);
    }

    public function getAbsencePolicyById($id){
        $absences = $this->common->commonGetById($id, 'id', 'absence_policy', '*');
        return response()->json(['data' => $absences], 200);
    }

    public function deleteAbsencePolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Absence Policy';
        $table = 'absence_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createAbsencePolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'rate' => 'required|numeric',
                    'wage_group_id' => 'nullable|integer',
                    'pay_stub_entry_account_id' => 'nullable|integer',
                    'accrual_policy_id' => 'nullable|integer',
                    'accrual_rate' => 'nullable|numeric',
                ]);

                $table = 'absence_policy';
                $inputArr = [
                    'company_id' => 1, // Replace with dynamic company ID if applicable
                    'name' => $request->name,
                    'type' => $request->type,
                    'rate' => $request->rate,
                    'wage_group_id' => $request->wage_group_id,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id,
                    'accrual_policy_id' => $request->accrual_policy_id,
                    'accrual_rate' => $request->accrual_rate,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $absencePolicyId = $this->common->commonSave($table, $inputArr);

                if ($absencePolicyId) {
                    return response()->json(['status' => 'success', 'message' => 'Absence policy created successfully', 'data' => ['id' => $absencePolicyId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create absence policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateAbsencePolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'rate' => 'required|numeric',
                    'wage_group_id' => 'nullable|integer',
                    'pay_stub_entry_account_id' => 'nullable|integer',
                    'accrual_policy_id' => 'nullable|integer',
                    'accrual_rate' => 'nullable|numeric',
                ]);
    
                $table = 'absence_policy';
                $idColumn = 'id';
                $inputArr = [
                    'name' => $request->name,
                    'type' => $request->type,
                    'rate' => $request->rate,
                    'wage_group_id' => $request->wage_group_id,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id,
                    'accrual_policy_id' => $request->accrual_policy_id,
                    'accrual_rate' => $request->accrual_rate,
                    'updated_by' => Auth::user()->id,
                ];
    
                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);
    
                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Absence policy updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update absence policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

}