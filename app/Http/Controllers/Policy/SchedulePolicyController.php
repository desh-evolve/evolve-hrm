<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class SchedulePolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view schedule policy', ['only' => ['index', 'getAllSchedulePolicies', 'getScheduleDropdownData']]);
        $this->middleware('permission:create schedule policy', ['only' => ['createPolicyGroup']]);
        $this->middleware('permission:update schedule policy', ['only' => ['updatePolicyGroup', 'getPolicyGroupById']]);
        $this->middleware('permission:delete schedule policy', ['only' => ['deleteSchedulePolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.schedule.index');
    }

    public function getScheduleDropdownData(){
        $absence_policy = $this->common->commonGetAll('absence_policy', '*');
        $break_policy = $this->common->commonGetAll('break_policy', '*');
        $meal_policy = $this->common->commonGetAll('meal_policy', '*');
        $overtime_policy = $this->common->commonGetAll('overtime_policy', '*');
        return response()->json([
            'data' => [
                'absence_policy' => $absence_policy,
                'break_policy' => $break_policy,
                'meal_policy' => $meal_policy,
                'overtime_policy' => $overtime_policy,
            ]
        ], 200);
    }

    public function getAllSchedulePolicies(){
        $fields = ['schedule_policy.*', 'meal_policy.name AS meal_policy', 'overtime_policy.name AS overtime_policy', 'absence_policy.name AS absence_policy'];
        $joinArr = [
            'meal_policy' => ['meal_policy.id', '=', 'schedule_policy.meal_policy_id'],
            'overtime_policy' => ['overtime_policy.id', '=', 'schedule_policy.over_time_policy_id'],
            'absence_policy' => ['absence_policy.id', '=', 'schedule_policy.absence_policy_id'],
        ];
        $schedules = $this->common->commonGetAll('schedule_policy', $fields, $joinArr);
        return response()->json(['data' => $schedules], 200);
    }

    public function getSchedulePolicyById($id){
        $connections = [
            'schedule_policy_break_policy' => [
                'con_fields' => ['break_policy_id'],  // Fields to select from connected table
                'con_where' => ['schedule_policy_break_policy.schedule_policy_id' => 'id'],  // Link to the main table 
                'con_joins' => [],
                'con_name' => 'break_policies',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];
        $schedules = $this->common->commonGetById($id, 'id', 'schedule_policy', '*', [], [], false, $connections);
        return response()->json(['data' => $schedules], 200);
    }

    public function deleteSchedulePolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Schedule Policy';
        $table = 'round_interval_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createSchedulePolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'schedule_name' => 'required|string|max:255',
                    'meal_policy_id' => 'nullable|integer',
                    'break_policy_ids' => 'nullable|string',
                    'absence_policy_id' => 'nullable|integer',
                    'overtime_policy_id' => 'nullable|integer',
                    'start_stop_window' => 'nullable|integer',
                ]);

                $table = 'schedule_policy';
                $inputArr = [
                    'company_id' => 1, // Adjust based on your context
                    'name' => $request->schedule_name,
                    'meal_policy_id' => $request->meal_policy_id,
                    'absence_policy_id' => $request->absence_policy_id,
                    'over_time_policy_id' => $request->overtime_policy_id,
                    'start_window' => $request->start_stop_window,
                    'start_stop_window' => $request->start_stop_window,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $schedulePolicyId = $this->common->commonSave($table, $inputArr);

                if (!empty($request->break_policy_ids)) {
                    $breakPolicyIds = explode(',', $request->break_policy_ids);
                    DB::table('schedule_policy_break_policy')->insert(array_map(function ($policyId) use ($schedulePolicyId) {
                        return ['schedule_policy_id' => $schedulePolicyId, 'break_policy_id' => $policyId];
                    }, $breakPolicyIds));
                }

                if ($schedulePolicyId) {
                    return response()->json(['status' => 'success', 'message' => 'Schedule policy created successfully', 'data' => ['id' => $schedulePolicyId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create schedule policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    
    public function updateSchedulePolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'schedule_name' => 'required|string|max:255',
                    'meal_policy_id' => 'nullable|integer',
                    'break_policy_ids' => 'nullable|string',
                    'absence_policy_id' => 'nullable|integer',
                    'overtime_policy_id' => 'nullable|integer',
                    'start_stop_window' => 'nullable|integer',
                ]);

                $table = 'schedule_policy';
                $idColumn = 'id';
                $inputArr = [
                    'name' => $request->schedule_name,
                    'meal_policy_id' => $request->meal_policy_id,
                    'absence_policy_id' => $request->absence_policy_id,
                    'over_time_policy_id' => $request->overtime_policy_id,
                    'start_window' => $request->start_stop_window,
                    'start_stop_window' => $request->start_stop_window,
                    'updated_by' => Auth::user()->id,
                ];

                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                DB::table('schedule_policy_break_policy')->where('schedule_policy_id', $id)->delete();
                if (!empty($request->break_policy_ids)) {
                    $breakPolicyIds = explode(',', $request->break_policy_ids);
                    if($request->break_policy_ids !== 0){
                        DB::table('schedule_policy_break_policy')->insert(array_map(function ($policyId) use ($id) {
                            return ['schedule_policy_id' => $id, 'break_policy_id' => $policyId];
                        }, $breakPolicyIds));
                    }
                }

                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Schedule policy updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update schedule policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }


}