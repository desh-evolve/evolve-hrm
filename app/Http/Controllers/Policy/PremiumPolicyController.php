<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PremiumPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view premium policy', ['only' => ['index', 'getAllPremiumPolicies']]);
        $this->middleware('permission:create premium policy', ['only' => ['form', 'getPremiumDropdownData', 'createPremiumPolicy']]);
        $this->middleware('permission:update premium policy', ['only' => ['form', 'getPremiumDropdownData', 'updatePremiumPolicy', 'getPremiumPolicyById']]);
        $this->middleware('permission:delete premium policy', ['only' => ['deletePremiumPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.premium.index');
    }

    public function form()
    {
        return view('policy.premium.form');
    }

    public function getPremiumDropdownData(){
        $wage_groups = $this->common->commonGetAll('com_wage_groups', ['id', 'wage_group_name AS name']);
        $pay_stubs = $this->common->commonGetAll('pay_stub_entry_account', '*');
        $accrual_policies = $this->common->commonGetAll('accrual_policy', '*');
        $branches = $this->common->commonGetAll('com_branches', ['id', 'branch_name AS name']);
        $departments = $this->common->commonGetAll('com_departments', ['id', 'department_name AS name']);
        $br_deps = $this->common->commonGetAll('com_branch_departments', '*');
        return response()->json([
            'data' => [
                'wage_groups' => $wage_groups,
                'pay_stubs' => $pay_stubs,
                'accrual_policies' => $accrual_policies,
                'branches' => $branches,
                'departments' => $departments,
                'br_deps' => $br_deps,
            ]
        ], 200);
    }

    public function getAllPremiumPolicies(){
        $table = 'premium_policy';
        $connections = [
            'policy_group_policies' => [
                'con_fields' => ['*'],
                'con_where' => [
                    'policy_group_policies.policy_table' => $table,
                    'policy_group_policies.policy_id' => 'id',
                    'policy_group.status' => 'active',
                ],
                'con_joins' => [
                    'policy_group' => ['policy_group.id', '=', 'policy_group_policies.policy_group_id']
                ],
                'con_name' => 'policy_groups',
                'except_deleted' => false,
            ],
        ];
        $premiums = $this->common->commonGetAll($table, '*', [], [], false, $connections);
        return response()->json(['data' => $premiums], 200);
    }

    public function getPremiumPolicyById($id){
        $connections = [
            'premium_policy_branch' => [
                'con_fields' => ['id', 'branch_id'],
                'con_where' => ['premium_policy_branch.premium_policy_id' => 'id'],
                'con_joins' => [],
                'con_name' => 'branches',
                'except_deleted' => true,
            ],
            'premium_policy_department' => [
                'con_fields' => ['id', 'department_id'],
                'con_where' => ['premium_policy_department.premium_policy_id' => 'id'],
                'con_joins' => [],
                'con_name' => 'departments',
                'except_deleted' => true,
            ],
        ];
        $premiums = $this->common->commonGetById($id, 'id', 'premium_policy', '*', [], [], false, $connections);
        return response()->json(['data' => $premiums], 200);
    }

    public function deletePremiumPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Premium Policy';
        $table = 'premium_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createPremiumPolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                // Validate the request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date|after_or_equal:start_date',
                    'start_time' => 'nullable|string',
                    'end_time' => 'nullable|string',
                    'include_partial_punch' => 'nullable|boolean',
                    'daily_trigger_time' => 'nullable|string',
                    'weekly_trigger_time' => 'nullable|string',
                    'sun' => 'nullable|boolean',
                    'mon' => 'nullable|boolean',
                    'tue' => 'nullable|boolean',
                    'wed' => 'nullable|boolean',
                    'thu' => 'nullable|boolean',
                    'fri' => 'nullable|boolean',
                    'sat' => 'nullable|boolean',
                    'daily_trigger_time2' => 'nullable|string',
                    'maximum_no_break_time' => 'nullable|integer',
                    'minimum_break_time' => 'nullable|integer',
                    'minimum_time_between_shift' => 'nullable|integer',
                    'minimum_first_shift_time' => 'nullable|integer',
                    'minimum_shift_time' => 'nullable|integer',
                    'minimum_time' => 'nullable|integer',
                    'maximum_time' => 'nullable|integer',
                    'include_meal_policy' => 'nullable|boolean',
                    'include_break_policy' => 'nullable|boolean',
                    'pay_type' => 'nullable|string',
                    'rate' => 'nullable|numeric',
                    'wage_group_id' => 'nullable|integer',
                    'pay_stub_entry_account_id' => 'nullable|integer',
                    'accrual_policy_id' => 'nullable|integer',
                    'premium_policy_id' => 'nullable|integer',
                    'branches' => 'nullable|json',
                    'departments' => 'nullable|json',
                ]);

                $policyData = [
                    'company_id' => 1, // Replace with actual logic if needed
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                
                    // Basic fields
                    'name' => $request->name,
                    'type' => $request->type,
                    'start_date' => $request->start_date ?: null,
                    'end_date' => $request->end_date ?: null,
                    'start_time' => $request->start_time !== null ? $request->start_time : null,
                    'end_time' => $request->end_time !== null ? $request->end_time : null,

                    'include_partial_punch' => $request->include_partial_punch ?: 0,
                
                    // Daily and Weekly Triggers
                    'daily_trigger_time' => $request->daily_trigger_time ?: 0,
                    'weekly_trigger_time' => $request->weekly_trigger_time ?: 0,
                
                    // Days of the week
                    'sun' => $request->sun ?: 0,
                    'mon' => $request->mon ?: 0,
                    'tue' => $request->tue ?: 0,
                    'wed' => $request->wed ?: 0,
                    'thu' => $request->thu ?: 0,
                    'fri' => $request->fri ?: 0,
                    'sat' => $request->sat ?: 0,
                
                    // Break and Shift Policies
                    'maximum_no_break_time' => $request->maximum_no_break_time ?: 0,
                    'minimum_break_time' => $request->minimum_break_time ?: 0,
                    'minimum_time_between_shift' => $request->minimum_time_between_shift ?: 0,
                    'minimum_first_shift_time' => $request->minimum_first_shift_time ?: 0,
                    'minimum_shift_time' => $request->minimum_shift_time ?: 0,
                
                    // Time-related fields
                    'minimum_time' => $request->minimum_time ?: 0,
                    'maximum_time' => $request->maximum_time ?: 0,
                
                    // Include Policies
                    'include_meal_policy' => $request->include_meal_policy ?: 0,
                    'include_break_policy' => $request->include_break_policy ?: 0,
                
                    // Pay-related fields
                    'pay_type' => $request->pay_type,
                    'rate' => floatval($request->rate) ?: 1.0000,
                    'wage_group_id' => $request->wage_group_id,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id ?: null,
                
                    // Foreign Key Policies
                    'accrual_policy_id' => $request->accrual_policy_id ?: null,
                    //'premium_policy_id' => $request->premium_policy_id ?: null,

                ];

                //print_r($policyData);exit;
                // Save the policy
                $premium_policy_id = $this->common->commonSave('premium_policy', $policyData);

                $this->saveBranches($premium_policy_id, $request);
                $this->saveDepartments($premium_policy_id, $request);

                if ($premium_policy_id) {
                    return response()->json(['status' => 'success', 'message' => 'Premium policy created successfully', 'data' => ['id' => $premium_policy_id]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create premium policy', 'data' => []], 500);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updatePremiumPolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Validate the request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'start_date' => 'nullable|date',
                    'end_date' => 'nullable|date|after_or_equal:start_date',
                    'start_time' => 'nullable|string',
                    'end_time' => 'nullable|string',
                    'include_partial_punch' => 'nullable|boolean',
                    'daily_trigger_time' => 'nullable|string',
                    'weekly_trigger_time' => 'nullable|string',
                    'sun' => 'nullable|boolean',
                    'mon' => 'nullable|boolean',
                    'tue' => 'nullable|boolean',
                    'wed' => 'nullable|boolean',
                    'thu' => 'nullable|boolean',
                    'fri' => 'nullable|boolean',
                    'sat' => 'nullable|boolean',
                    'maximum_no_break_time' => 'nullable|integer',
                    'minimum_break_time' => 'nullable|integer',
                    'minimum_time_between_shift' => 'nullable|integer',
                    'minimum_first_shift_time' => 'nullable|integer',
                    'minimum_shift_time' => 'nullable|integer',
                    'minimum_time' => 'nullable|integer',
                    'maximum_time' => 'nullable|integer',
                    'include_meal_policy' => 'nullable|boolean',
                    'include_break_policy' => 'nullable|boolean',
                    'pay_type' => 'nullable|string',
                    'rate' => 'nullable|numeric',
                    'wage_group_id' => 'nullable|integer',
                    'pay_stub_entry_account_id' => 'nullable|integer',
                    'accrual_policy_id' => 'nullable|integer',
                    'premium_policy_id' => 'nullable|integer',
                ]);

                // Prepare the data for update
                $policyData = [
                    'company_id' => 1, // Adjust as per your logic
                    'updated_by' => Auth::user()->id,
                    'name' => $request->name,
                    'type' => $request->type,
                    'start_date' => $request->start_date ?: null,
                    'end_date' => $request->end_date ?: null,
                    'start_time' => $request->start_time ?: null,
                    'end_time' => $request->end_time ?: null,
                    'include_partial_punch' => $request->include_partial_punch ?: 0,
                    'daily_trigger_time' => $request->daily_trigger_time ?: 0,
                    'weekly_trigger_time' => $request->weekly_trigger_time ?: 0,
                    'sun' => $request->sun ?: 0,
                    'mon' => $request->mon ?: 0,
                    'tue' => $request->tue ?: 0,
                    'wed' => $request->wed ?: 0,
                    'thu' => $request->thu ?: 0,
                    'fri' => $request->fri ?: 0,
                    'sat' => $request->sat ?: 0,
                    'maximum_no_break_time' => $request->maximum_no_break_time ?: 0,
                    'minimum_break_time' => $request->minimum_break_time ?: 0,
                    'minimum_time_between_shift' => $request->minimum_time_between_shift ?: 0,
                    'minimum_first_shift_time' => $request->minimum_first_shift_time ?: 0,
                    'minimum_shift_time' => $request->minimum_shift_time ?: 0,
                    'minimum_time' => $request->minimum_time ?: 0,
                    'maximum_time' => $request->maximum_time ?: 0,
                    'include_meal_policy' => $request->include_meal_policy ?: 0,
                    'include_break_policy' => $request->include_break_policy ?: 0,
                    'pay_type' => $request->pay_type ?: null,
                    'rate' => floatval($request->rate) ?: 1.0000,
                    'wage_group_id' => $request->wage_group_id,
                    'pay_stub_entry_account_id' => $request->pay_stub_entry_account_id ?: null,
                    'accrual_policy_id' => $request->accrual_policy_id ?: null,
                ];

                // Update the policy
                $updated = $this->common->commonSave('premium_policy', $policyData, $id, 'id');

                // Remove old policies and save new ones
                DB::table('premium_policy_branch')->where('premium_policy_id', $id)->delete();
                DB::table('premium_policy_department')->where('premium_policy_id', $id)->delete();
                $this->saveBranches($id, $request);
                $this->saveDepartments($id, $request);

                if ($updated) {
                    return response()->json(['status' => 'success', 'message' => 'Premium policy updated successfully', 'data' => ['id' => $id]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update premium policy', 'data' => []], 500);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    private function saveBranches($premiumPolicyId, $request)
    {
        if (!empty($request->branches)) {
            $brIds = json_decode($request->branches, true);
            if (is_array($brIds)) {
                foreach ($brIds as $brId) {
                    DB::table('premium_policy_branch')->insert([
                        'premium_policy_id' => $premiumPolicyId,
                        'branch_id' => $brId,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                }
            }
        }
    }

    private function saveDepartments($premiumPolicyId, $request)
    {
        if (!empty($request->departments)) {
            $depIds = json_decode($request->departments, true);
            if (is_array($depIds)) {
                foreach ($depIds as $depId) {
                    DB::table('premium_policy_department')->insert([
                        'premium_policy_id' => $premiumPolicyId,
                        'department_id' => $depId,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                }
            }
        }
    }

}