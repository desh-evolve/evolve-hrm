<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AccrualPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view accrual policy', ['only' => ['index', 'getAllAccrualPolicies']]);
        $this->middleware('permission:create accrual policy', ['only' => ['form', 'createAccrualPolicy', '']]);
        $this->middleware('permission:update accrual policy', ['only' => ['form', 'updateAccrualPolicy', 'getAccrualPolicyById']]);
        $this->middleware('permission:delete accrual policy', ['only' => ['deleteAccrualPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.accrual.index');
    }

    public function form()
    {
        return view('policy.accrual.form');
    }

    public function getAllAccrualPolicies(){
        $table = 'accrual_policy';
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
        $accruals = $this->common->commonGetAll($table, '*', [], [], false, $connections);
        return response()->json(['data' => $accruals], 200);
    }

    public function getAccrualPolicyById($id){
        $connections = [
            'accrual_policy_milestone' => [
                'con_fields' => ['*'],  // Fields to select from connected table
                'con_where' => ['accrual_policy_milestone.accrual_policy_id' => 'id'],  // Link to the main table 
                'con_joins' => [],
                'con_name' => 'milestones',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];
        $accruals = $this->common->commonGetById($id, 'id', 'accrual_policy', '*', [], [], false, $connections);
        return response()->json(['data' => $accruals], 200);
    }

    public function deleteAccrualPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Accrual Policy';
        $table = 'accrual_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }
    public function createAccrualPolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
    
                // Validate the request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'enable_pay_stub_balance_display' => 'nullable|boolean',
                    'apply_frequency' => 'nullable|string',
                    'apply_frequency_month' => 'nullable|integer|min:1|max:12',
                    'apply_frequency_day_of_month' => 'nullable|integer|min:1|max:31',
                    'apply_frequency_day_of_week' => 'nullable|integer|min:0|max:6',
                    'minimum_employed_days' => 'nullable|integer',
                    'minimum_employed_days_catchup' => 'nullable|integer',
                    'milestone_rollover_hire_date' => 'nullable|boolean',
                    'milestone_rollover_month' => 'nullable|integer|min:1|max:12',
                    'milestone_rollover_day_of_month' => 'nullable|integer|min:1|max:31',
                    'apply_frequency_hire_date' => 'nullable|boolean',
                    'minimum_time' => 'nullable|integer', // Added validation
                    'maximum_time' => 'nullable|integer', // Added validation
                    'milestones' => 'nullable|array', // Milestones data validation
                    'milestones.*.length_of_service' => 'nullable|numeric|min:0',
                    'milestones.*.length_of_service_unit' => 'nullable|string',
                    'milestones.*.accrual_rate' => 'nullable|numeric|min:0',
                    'milestones.*.maximum_time' => 'nullable|integer|min:0',
                    'milestones.*.rollover_time' => 'nullable|integer|min:0',
                ]);
    
                // Prepare data for insertion
                $controlData = [
                    'company_id' => Auth::user()->company_id ?? 1, // Replace with actual logic
                    'name' => $request->input('name'),
                    'type' => $request->input('type'),
                    'minimum_time' => $request->input('minimum_time', 0),
                    'maximum_time' => $request->input('maximum_time', 0),
                    'apply_frequency' => $request->input('apply_frequency'),
                    'apply_frequency_month' => $request->input('apply_frequency_month'),
                    'apply_frequency_day_of_month' => $request->input('apply_frequency_day_of_month'),
                    'apply_frequency_day_of_week' => $request->input('apply_frequency_day_of_week'),
                    'milestone_rollover_hire_date' => $request->input('milestone_rollover_hire_date', false),
                    'milestone_rollover_month' => $request->input('milestone_rollover_month'),
                    'milestone_rollover_day_of_month' => $request->input('milestone_rollover_day_of_month'),
                    'minimum_employed_days' => $request->input('minimum_employed_days', 0),
                    'minimum_employed_days_catchup' => $request->input('minimum_employed_days_catchup', 0),
                    'enable_pay_stub_balance_display' => $request->input('enable_pay_stub_balance_display', false),
                    'apply_frequency_hire_date' => $request->input('apply_frequency_hire_date', false),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];
    
                $accrualPolicyId = $this->common->commonSave('accrual_policy', $controlData);
    
                // Handle milestones if provided
                if ($request->has('milestones')) {
                    foreach ($request->input('milestones') as $milestone) {
                        $milestoneData = [
                            'accrual_policy_id' => $accrualPolicyId,
                            'length_of_service' => $milestone['length_of_service'] ?? null,
                            'length_of_service_unit' => $milestone['length_of_service_unit'] ?? null,
                            'accrual_rate' => $milestone['accrual_rate'] ?? null,
                            'minimum_time' => $milestone['minimum_time'] ?? null,
                            'maximum_time' => $milestone['maximum_time'] ?? null,
                            'rollover_time' => $milestone['rollover_time'] ?? null,
                            'status' => 'active',
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ];
                        $this->common->commonSave('accrual_policy_milestone', $milestoneData);
                    }
                }
    
                return response()->json(['status' => 'success', 'message' => 'Accrual policy created successfully', 'data' => ['id' => $accrualPolicyId]], 200);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateAccrualPolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {

                // Validate the request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'enable_pay_stub_balance_display' => 'nullable|boolean',
                    'apply_frequency' => 'nullable|string',
                    'apply_frequency_month' => 'nullable|integer|min:1|max:12',
                    'apply_frequency_day_of_month' => 'nullable|integer|min:1|max:31',
                    'apply_frequency_day_of_week' => 'nullable|integer|min:0|max:6',
                    'minimum_employed_days' => 'nullable|integer',
                    'minimum_employed_days_catchup' => 'nullable|integer',
                    'milestone_rollover_hire_date' => 'nullable|boolean',
                    'milestone_rollover_month' => 'nullable|integer|min:1|max:12',
                    'milestone_rollover_day_of_month' => 'nullable|integer|min:1|max:31',
                    'apply_frequency_hire_date' => 'nullable|boolean',
                    'minimum_time' => 'nullable|integer',
                    'maximum_time' => 'nullable|integer',
                    'milestones' => 'nullable|array',
                    'milestones.*.id' => 'nullable|integer|exists:accrual_policy_milestone,id',
                    'milestones.*.length_of_service' => 'nullable|numeric|min:0',
                    'milestones.*.length_of_service_unit' => 'nullable|string',
                    'milestones.*.accrual_rate' => 'nullable|numeric|min:0',
                    'milestones.*.maximum_time' => 'nullable|integer|min:0',
                    'milestones.*.rollover_time' => 'nullable|integer|min:0',
                ]);

                // Update the main policy data
                $controlData = [
                    'name' => $request->input('name'),
                    'type' => $request->input('type'),
                    'minimum_time' => $request->input('minimum_time', 0),
                    'maximum_time' => $request->input('maximum_time', 0),
                    'apply_frequency' => $request->input('apply_frequency'),
                    'apply_frequency_month' => $request->input('apply_frequency_month'),
                    'apply_frequency_day_of_month' => $request->input('apply_frequency_day_of_month'),
                    'apply_frequency_day_of_week' => $request->input('apply_frequency_day_of_week'),
                    'milestone_rollover_hire_date' => $request->input('milestone_rollover_hire_date', false),
                    'milestone_rollover_month' => $request->input('milestone_rollover_month'),
                    'milestone_rollover_day_of_month' => $request->input('milestone_rollover_day_of_month'),
                    'minimum_employed_days' => $request->input('minimum_employed_days', 0),
                    'minimum_employed_days_catchup' => $request->input('minimum_employed_days_catchup', 0),
                    'enable_pay_stub_balance_display' => $request->input('enable_pay_stub_balance_display', false),
                    'apply_frequency_hire_date' => $request->input('apply_frequency_hire_date', false),
                    'updated_by' => Auth::id(),
                ];

                $this->common->commonSave('accrual_policy', $controlData, $id, 'id');

                // Handle milestones
                if ($request->has('milestones')) {
                    $existingMilestoneIds = collect($request->input('milestones'))
                        ->pluck('id')
                        ->filter()
                        ->all();

                    // Remove milestones not in the request
                    DB::table('accrual_policy_milestone')
                        ->where('accrual_policy_id', $id)
                        ->whereNotIn('id', $existingMilestoneIds)
                        ->delete();

                    // Update or create milestones
                    foreach ($request->input('milestones') as $milestone) {
                        $milestoneData = [
                            'length_of_service' => $milestone['length_of_service'] ?? null,
                            'length_of_service_unit' => $milestone['length_of_service_unit'] ?? null,
                            'accrual_rate' => $milestone['accrual_rate'] ?? null,
                            'minimum_time' => $milestone['minimum_time'] ?? null,
                            'maximum_time' => $milestone['maximum_time'] ?? null,
                            'rollover_time' => $milestone['rollover_time'] ?? null,
                            'updated_by' => Auth::id(),
                        ];

                        if (!empty($milestone['id'])) {
                            // Update existing milestone
                            $this->common->commonSave('accrual_policy_milestone', $milestoneData, $milestone['id'], 'id');
                        } else {
                            // Create new milestone
                            $milestoneData['accrual_policy_id'] = $id;
                            $milestoneData['created_by'] = Auth::id();
                            $milestoneData['status'] = 'active';
                            $this->common->commonSave('accrual_policy_milestone', $milestoneData);
                        }
                    }
                }

                return response()->json(['status' => 'success', 'message' => 'Accrual policy updated successfully'], 200);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }    

}