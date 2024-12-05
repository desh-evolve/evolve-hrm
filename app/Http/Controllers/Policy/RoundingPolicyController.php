<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class RoundingPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view rounding policy', ['only' => ['index', 'getAllRoundingPolicies']]);
        $this->middleware('permission:create rounding policy', ['only' => ['form', 'getRoundingDropdownData', 'createRoundingPolicy']]);
        $this->middleware('permission:update rounding policy', ['only' => ['form', 'getRoundingDropdownData', 'updateRoundingPolicy', 'getRoundingPolicyById']]);
        $this->middleware('permission:delete rounding policy', ['only' => ['deleteRoundingPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.rounding.index');
    }

    public function getRoundingDropdownData(){
        $punch_types = $this->common->commonGetAll('round_interval_punch_types', '*');
        return response()->json([
            'data' => [
                'punch_types' => $punch_types,
            ]
        ], 200);
    }

    public function getAllRoundingPolicies(){
        $table = 'round_interval_policy';
        $fields = ['round_interval_policy.id', 'round_interval_policy.name', 'round_interval_policy.punch_type_id', 'round_type', 'round_interval', 'strict', 'grace', 'round_interval_punch_types.name AS punch_type'];
        $joinArr = [
            'round_interval_punch_types' => ['round_interval_punch_types.id', '=', 'round_interval_policy.punch_type_id']
        ];
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
        $roundings = $this->common->commonGetAll($table, $fields, $joinArr, [], false, $connections);
        return response()->json(['data' => $roundings], 200);
    }

    public function getRoundingPolicyById($id){
        $roundings = $this->common->commonGetById($id, 'id', 'round_interval_policy', '*');
        return response()->json(['data' => $roundings], 200);
    }

    public function deleteRoundingPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Rounding Policy';
        $table = 'round_interval_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createRoundingPolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'punch_type_id' => 'required|integer',
                    'round_type' => 'nullable|string|in:down,average,up',
                    'round_interval' => 'required|integer|min:1',
                    'strict' => 'nullable|boolean',
                    'grace' => 'nullable|integer',
                    'minimum' => 'nullable|integer',
                    'maximum' => 'nullable|integer',
                ]);

                $table = 'round_interval_policy';
                $inputArr = [
                    'company_id' => 1, // Replace with dynamic company ID if applicable
                    'name' => $request->name,
                    'punch_type_id' => $request->punch_type_id,
                    'round_type' => $request->round_type,
                    'round_interval' => $request->round_interval,
                    'strict' => $request->strict ?? 0,
                    'grace' => $request->grace,
                    'minimum' => $request->minimum,
                    'maximum' => $request->maximum,
                    'status' => 'active', // Default status
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $roundingPolicyId = $this->common->commonSave($table, $inputArr);

                if ($roundingPolicyId) {
                    return response()->json(['status' => 'success', 'message' => 'Rounding policy created successfully', 'data' => ['id' => $roundingPolicyId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create rounding policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }


    public function updateRoundingPolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'punch_type_id' => 'required|integer',
                    'round_type' => 'nullable|string|in:down,average,up',
                    'round_interval' => 'required|integer|min:1',
                    'strict' => 'nullable|boolean',
                    'grace' => 'nullable|integer',
                    'minimum' => 'nullable|integer',
                    'maximum' => 'nullable|integer',
                ]);
    
                $table = 'round_interval_policy';
                $idColumn = 'id';
                $inputArr = [
                    'name' => $request->name,
                    'punch_type_id' => $request->punch_type_id,
                    'round_type' => $request->round_type,
                    'round_interval' => $request->round_interval,
                    'strict' => $request->strict ?? 0,
                    'grace' => $request->grace,
                    'minimum' => $request->minimum,
                    'maximum' => $request->maximum,
                    'updated_by' => Auth::user()->id,
                ];
    
                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);
    
                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Rounding policy updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update rounding policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    

}