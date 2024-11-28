<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class HolidayPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view holiday policy', ['only' => ['index', 'getAllHolidayPolicies']]);
        $this->middleware('permission:create holiday policy', ['only' => ['form', 'getHolidayDropdownData', 'createHolidayPolicy']]);
        $this->middleware('permission:update holiday policy', ['only' => ['form', 'getHolidayDropdownData', 'updateHolidayPolicy', 'getHolidayPolicyById']]);
        $this->middleware('permission:delete holiday policy', ['only' => ['deleteHolidayPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.holiday.index');
    }

    public function form()
    {
        return view('policy.holiday.form');
    }

    public function getHolidayDropdownData(){
        $rounding_policies = $this->common->commonGetAll('round_interval_policy', '*');
        $absence_policies = $this->common->commonGetAll('absence_policy', '*');
        return response()->json([
            'data' => [
                'rounding_policies' => $rounding_policies,
                'absence_policies' => $absence_policies,
            ]
        ], 200);
    }

    public function getAllHolidayPolicies(){
        $holidays = $this->common->commonGetAll('holiday_policy', '*');
        return response()->json(['data' => $holidays], 200);
    }

    public function getHolidayPolicyById($id){
        $holidays = $this->common->commonGetById($id, 'id', 'holiday_policy', '*');
        return response()->json(['data' => $holidays], 200);
    }

    public function deleteHolidayPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Holiday Policy';
        $table = 'holiday_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createHolidayPolicy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string|max:255',
                    'default_schedule_status' => 'required|string|max:255',
                    'minimum_employed_days' => 'required|integer',
                    'minimum_worked_period_days' => 'nullable|integer',
                    'minimum_worked_days' => 'nullable|integer',
                    'average_time_days' => 'nullable|integer',
                    'minimum_time' => 'nullable|integer',
                    'maximum_time' => 'nullable|integer',
                    'time' => 'nullable|integer',
                    'absence_policy_id' => 'nullable|integer',
                    'round_interval_policy_id' => 'nullable|integer',
                    'force_over_time_policy' => 'nullable|boolean',
                    'include_over_time' => 'nullable|boolean',
                    'include_paid_absence_time' => 'nullable|boolean',
                    'average_time_worked_days' => 'nullable|boolean',
                    'worked_scheduled_days' => 'nullable|string',
                    'minimum_worked_after_period_days' => 'nullable|integer',
                    'minimum_worked_after_days' => 'nullable|integer',
                    'worked_after_scheduled_days' => 'nullable|string',
                    'average_days' => 'nullable|integer',
                ]);
                
                $table = 'holiday_policy';
                
                $inputArr = [
                    'company_id' => 1, // Replace with dynamic company ID if applicable
                    'name' => $request->name,
                    'type' => $request->type,
                    'default_schedule_status' => $request->default_schedule_status,
                    'minimum_employed_days' => $request->minimum_employed_days,
                    'minimum_worked_period_days' => $request->minimum_worked_period_days,
                    'minimum_worked_days' => $request->minimum_worked_days,
                    'average_time_days' => $request->average_time_days,
                    'include_over_time' => $request->include_over_time ?? 0,
                    'include_paid_absence_time' => $request->include_paid_absence_time ?? 0,
                    'minimum_time' => $request->minimum_time,
                    'maximum_time' => $request->maximum_time,
                    'time' => $request->time,
                    'absence_policy_id' => $request->absence_policy_id,
                    'round_interval_policy_id' => $request->round_interval_policy_id,
                    'force_over_time_policy' => $request->force_over_time_policy ?? 0,
                    'average_time_worked_days' => $request->average_time_worked_days ?? 0,
                    'worked_scheduled_days' => $request->worked_scheduled_days,
                    'minimum_worked_after_days' => $request->minimum_worked_after_days,
                    'minimum_worked_after_period_days' => $request->minimum_worked_after_period_days,
                    'worked_after_scheduled_days' => $request->worked_after_scheduled_days,
                    'average_days' => $request->average_days,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
    
                $holidayPolicyId = $this->common->commonSave($table, $inputArr);
    
                if ($holidayPolicyId) {
                    return response()->json(['status' => 'success', 'message' => 'Holiday policy created successfully', 'data' => ['id' => $holidayPolicyId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create holiday policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    public function updateHolidayPolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string|max:255',
                    'default_schedule_status' => 'required|string|max:255',
                    'minimum_employed_days' => 'required|integer',
                    'minimum_worked_period_days' => 'nullable|integer',
                    'minimum_worked_days' => 'nullable|integer',
                    'average_time_days' => 'nullable|integer',
                    'include_over_time' => 'nullable|boolean',
                    'include_paid_absence_time' => 'nullable|boolean',
                    'minimum_time' => 'nullable|integer',
                    'maximum_time' => 'nullable|integer',
                    'time' => 'nullable|integer',
                    'absence_policy_id' => 'nullable|integer',
                    'round_interval_policy_id' => 'nullable|integer',
                    'force_over_time_policy' => 'nullable|boolean',
                    'average_time_worked_days' => 'nullable|boolean',
                    'worked_scheduled_days' => 'nullable|string',
                    'minimum_worked_after_period_days' => 'nullable|integer',
                    'minimum_worked_after_days' => 'nullable|integer',
                    'worked_after_scheduled_days' => 'nullable|string',
                    'average_days' => 'nullable|integer',
                ]);
    
                $table = 'holiday_policy';
                $idColumn = 'id';
                $inputArr = [
                    'name' => $request->name,
                    'type' => $request->type,
                    'default_schedule_status' => $request->default_schedule_status,
                    'minimum_employed_days' => $request->minimum_employed_days,
                    'minimum_worked_period_days' => $request->minimum_worked_period_days,
                    'minimum_worked_days' => $request->minimum_worked_days,
                    'average_time_days' => $request->average_time_days,
                    'include_over_time' => $request->include_over_time ?? 0,
                    'include_paid_absence_time' => $request->include_paid_absence_time ?? 0,
                    'minimum_time' => $request->minimum_time,
                    'maximum_time' => $request->maximum_time,
                    'time' => $request->time,
                    'absence_policy_id' => $request->absence_policy_id,
                    'round_interval_policy_id' => $request->round_interval_policy_id,
                    'force_over_time_policy' => $request->force_over_time_policy ?? 0,
                    'average_time_worked_days' => $request->average_time_worked_days ?? 0,
                    'worked_scheduled_days' => $request->worked_scheduled_days,
                    'minimum_worked_after_period_days' => $request->minimum_worked_after_period_days,
                    'minimum_worked_after_days' => $request->minimum_worked_after_days,
                    'worked_after_scheduled_days' => $request->worked_after_scheduled_days,
                    'average_days' => $request->average_days,
                    'updated_by' => Auth::user()->id,
                ];
    
                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);
    
                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Holiday policy updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update holiday policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    


}