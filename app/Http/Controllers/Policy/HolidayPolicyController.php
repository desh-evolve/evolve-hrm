<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

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
        $table = 'holiday_policy';
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
        $holidays = $this->common->commonGetAll($table, '*', [], [], false, $connections);
        return response()->json(['data' => $holidays], 200);
    }

    public function getHolidayPolicyById($id){
        $connections = [
            'holidays' => [
                'con_fields' => ['id AS holiday_id', 'name AS holiday_name', 'date_stamp AS holiday_date'],  // Fields to select from connected table
                'con_where' => ['holidays.holiday_policy_id' => 'id'],  // Link to the main table
                'con_joins' => [],
                'con_name' => 'holidays',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];

        $holidays = $this->common->commonGetById($id, 'id', 'holiday_policy', '*', [], [], false, $connections);
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
                    'types' => 'required|string|max:255',
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
                    'holiday_names' => 'nullable|array',
                    'holiday_dates' => 'nullable|array',
                ]);

                // Additional validation: ensure holiday_name and holiday_date match in count
                if (!empty($request->holiday_name) && count($request->holiday_name) !== count($request->holiday_date)) {
                    return response()->json(['error' => 'Mismatch between holiday names and dates'], 400);
                }

                $table = 'holiday_policy';

                $inputArr = [
                    'company_id' => 1, // Replace with dynamic company ID if applicable
                    'name' => $request->name,
                    'type' => $request->types,
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
                // Process holiday_name and holiday_date arrays
                if (!empty($request->holiday_names)) {
                    foreach ($request->holiday_names as $key => $name) {
                        $date = $request->holiday_dates[$key] ?? null;

                        // Skip if the date is missing or invalid
                        if ($name && $date) {
                            $holidayData = [
                                'holiday_policy_id' => $holidayPolicyId,
                                'name' => $name,
                                'date_stamp' => $date,
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ];

                            // Save holiday to the database
                            $this->common->commonSave('holidays', $holidayData);
                        }
                    }
                }


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
                    'types' => 'required|string|max:255',
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
                    'holiday_names' => 'nullable|array',
                    'holiday_dates' => 'nullable|array',
                ]);

                $table = 'holiday_policy';
                $idColumn = 'id';

                $inputArr = [
                    'name' => $request->name,
                    'type' => $request->types,
                    'default_schedule_status' => $request->default_schedule_status,
                    'minimum_employed_days' => $request->minimum_employed_days,
                    'minimum_worked_period_days' => $request->minimum_worked_period_days,
                    'minimum_worked_days' => $request->minimum_worked_days,
                    'average_time_days' => $request->average_time_days,
                    'include_over_time' => $request->include_over_time ?? 0,
                    'include_paid_absence_time' => $request->include_paid_absence_time ?? 0,
                    'minimum_time' => $request->minimum_time,
                    'maximum_time' => $request->maximum_time,
                    'time' => $request->time ?? 0,
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

                 // Update holidays
                if (!empty($request->holiday_names)) {
                    $existingHolidays = DB::table('holidays')
                        ->where('holiday_policy_id', $id)
                        ->get()
                        ->keyBy('id');

                    $updatedHolidayIds = [];

                    foreach ($request->holiday_names as $key => $name) {
                        $date = $request->holiday_dates[$key] ?? null;

                        if ($name && $date) {
                            $holidayData = [
                                'holiday_policy_id' => $id,
                                'name' => $name,
                                'date_stamp' => $date,
                                'updated_by' => Auth::user()->id,
                            ];

                            if (isset($existingHolidays[$key])) {
                                // Update existing holiday
                                $holidayId = $key;
                                DB::table('holidays')->where('id', $holidayId)->update($holidayData);
                                $updatedHolidayIds[] = $holidayId;
                            } else {
                                // Insert new holiday
                                $holidayData['created_by'] = Auth::user()->id;
                                $newHolidayId = DB::table('holidays')->insertGetId($holidayData);
                                $updatedHolidayIds[] = $newHolidayId;
                            }
                        }
                    }

                    // Remove holidays that are no longer in the request
                    DB::table('holidays')
                    ->where('holiday_policy_id', $id)
                    ->whereNotIn('id', $updatedHolidayIds)
                    ->delete();
                }

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
