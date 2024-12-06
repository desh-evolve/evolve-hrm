<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PolicyGroupsController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view policy group', ['only' => ['index', 'getAllPolicyGroups']]);
        $this->middleware('permission:create policy group', ['only' => ['form', 'getPolicyGroupDropdownData', 'createPolicyGroup']]);
        $this->middleware('permission:update policy group', ['only' => ['form', 'getPolicyGroupDropdownData', 'getPolicyGroupById', 'updatePolicyGroup']]);
        $this->middleware('permission:delete policy group', ['only' => ['deletePolicyGroup']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.policy_groups.index');
    }

    public function form()
    {
        return view('policy.policy_groups.form');
    }

    public function getPolicyGroupDropdownData(){
        $overtime_policies = $this->common->commonGetAll('overtime_policy', ['id', 'name']);
        $rounding_policies = $this->common->commonGetAll('round_interval_policy', ['id', 'name']);
        $meal_policies = $this->common->commonGetAll('meal_policy', ['id', 'name']);
        $break_policies = $this->common->commonGetAll('break_policy', ['id', 'name']);
        $accrual_policies = $this->common->commonGetAll('accrual_policy', ['id', 'name']);
        $premium_policies = $this->common->commonGetAll('premium_policy', ['id', 'name']);
        $holiday_policies = $this->common->commonGetAll('holiday_policy', ['id', 'name']);
        $exception_policies = $this->common->commonGetAll('exception_policy_control', ['id', 'name']);
        $employees = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        return response()->json([
            'data' => [
                'overtime_policies' => $overtime_policies,
                'rounding_policies' => $rounding_policies,
                'meal_policies' => $meal_policies,
                'break_policies' => $break_policies,
                'accrual_policies' => $accrual_policies,
                'premium_policies' => $premium_policies,
                'holiday_policies' => $holiday_policies,
                'exception_policies' => $exception_policies,
                'employees' => $employees,
            ]
        ], 200);
    }

    public function getAllPolicyGroups(){
        $pg = $this->common->commonGetAll('policy_group', '*');
        return response()->json(['data' => $pg], 200);
    }

    public function getPolicyGroupById($id){
        $connections = [
            'policy_group_employees' => [
                'con_fields' => ['employee_id'],  // Fields to select from connected table
                'con_where' => ['policy_group_employees.policy_group_id' => 'id'],  // Link to the main table 
                'con_joins' => [],
                'con_name' => 'employees',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
            'policy_group_policies' => [
                'con_fields' => ['policy_table', 'policy_id'],  // Fields to select from connected table
                'con_where' => ['policy_group_policies.policy_group_id' => 'id'],  // Link to the main table 
                'con_joins' => [],
                'con_name' => 'policies',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];

        $pg = $this->common->commonGetById($id, 'id', 'policy_group', '*', [], [], false, $connections);
        return response()->json(['data' => $pg], 200);
    }

    public function deletePolicyGroup($id){
        $whereArr = ['id' => $id];
        $title = 'Policy Group';
        $table = 'policy_group';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    /**
     * Create a new policy group with associated policies.
     */
    public function createPolicyGroup(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    // Each policy type is optional, but must be JSON if provided
                    'over_time_policy_ids' => 'nullable|json',
                    'round_interval_policy_ids' => 'nullable|json',
                    'meal_policy_ids' => 'nullable|json',
                    'break_policy_ids' => 'nullable|json',
                    'accrual_policy_ids' => 'nullable|json',
                    'premium_policy_ids' => 'nullable|json',
                    'holiday_policy_ids' => 'nullable|json',
                    'exception_policy_control_id' => 'nullable|integer',
                    'employee_ids' => 'nullable|json',
                ]);

                $policyGroupInput = [
                    'company_id' => 1, // Replace with dynamic company ID
                    'name' => $request->name,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                // Insert into `policy_group`
                $policyGroupId = $this->common->commonSave('policy_group', $policyGroupInput);

                if (!$policyGroupId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create policy group'], 500);
                }

                // Save associated policies
                $this->savePolicies($policyGroupId, $request);
                $this->savePolicyEmployees($policyGroupId, $request);

                return response()->json(['status' => 'success', 'message' => 'Policy group created successfully', 'data' => ['id' => $policyGroupId]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing policy group with associated policies.
     */
    public function updatePolicyGroup(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'over_time_policy_ids' => 'nullable|json',
                    'round_interval_policy_ids' => 'nullable|json',
                    'meal_policy_ids' => 'nullable|json',
                    'break_policy_ids' => 'nullable|json',
                    'accrual_policy_ids' => 'nullable|json',
                    'premium_policy_ids' => 'nullable|json',
                    'holiday_policy_ids' => 'nullable|json',
                    'exception_policy_control_id' => 'nullable|integer',
                    'employee_ids' => 'nullable|json',
                ]);

                $policyGroupInput = [
                    'name' => $request->name,
                    'updated_by' => Auth::user()->id,
                ];

                // Update the `policy_group` table
                $updated = $this->common->commonSave('policy_group', $policyGroupInput, $id, 'id');

                if (!$updated) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update policy group'], 500);
                }

                // Remove old policies and save new ones
                DB::table('policy_group_policies')->where('policy_group_id', $id)->delete();
                $this->savePolicies($id, $request);
                $this->savePolicyEmployees($id, $request);

                return response()->json(['status' => 'success', 'message' => 'Policy group updated successfully', 'data' => ['id' => $id]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Save associated policies for a policy group.
     *
     * @param int $policyGroupId
     * @param Request $request
     */
    private function savePolicies($policyGroupId, $request)
    {
        $policyTypes = [
            'over_time_policy_ids' => 'overtime_policy',
            'round_interval_policy_ids' => 'round_interval_policy',
            'meal_policy_ids' => 'meal_policy',
            'break_policy_ids' => 'break_policy',
            'accrual_policy_ids' => 'accrual_policy',
            'premium_policy_ids' => 'premium_policy',
            'holiday_policy_ids' => 'holiday_policy',
            'exception_policy_control_id' => 'exception_policy_control',
        ];

        foreach ($policyTypes as $field => $policyTable) {
            if (!empty($request->$field)) {
                $policyIds = json_decode($request->$field, true);
                if (is_array($policyIds)) {
                    foreach ($policyIds as $policyId) {
                        DB::table('policy_group_policies')->insert([
                            'policy_group_id' => $policyGroupId,
                            'policy_table' => $policyTable,
                            'policy_id' => $policyId,
                        ]);
                    }
                }else if($field === 'exception_policy_control_id'){
                    DB::table('policy_group_policies')->insert([
                        'policy_group_id' => $policyGroupId,
                        'policy_table' => $policyTable,
                        'policy_id' => $request->$field,
                    ]);
                }
            }
        }
    }

    private function savePolicyEmployees($policyGroupId, $request)
    {
        if (!empty($request->employee_ids)) {
            $empIds = json_decode($request->employee_ids, true);
            if (is_array($empIds)) {
                foreach ($empIds as $empId) {
                    DB::table('policy_group_employees')->insert([
                        'policy_group_id' => $policyGroupId,
                        'employee_id' => $empId,
                    ]);
                }
            }
        }
    }

}