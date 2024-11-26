<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Termwind\Components\Dd;
use Carbon\Carbon;

class MassPunchController extends Controller
{
    private $common = null;

    public function __construct()
    {
        // ========= need to develop getEmployeeList login as a staff =========== //

        $this->middleware('permission:view punch', ['only' => [
            'index',
            // 'getAllEmployeePunch',
            'getAllEmployeeList',
            'getEmployeePunchById',
            'getSingleEmployeePunch',
        ]]);
        $this->middleware('permission:create punch', ['only' => ['createEmployeePunch']]);
        $this->middleware('permission:update punch', ['only' => ['updateEmployeePunch']]);
        $this->middleware('permission:delete punch', ['only' => ['deleteEmployeePunch']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('attendance.mass_punch.index');
    }

    public function createEmployeePunch(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'punch_type' => 'required',
                    'punch_status' => 'required',
                ]);

                $employees = $request->employees;
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $selectedDays = json_decode($request->selectedDays, true);

                $daysOfWeek = [
                    'Sun' => 0,
                    'Mon' => 1,
                    'Tue' => 2,
                    'Wed' => 3,
                    'Thu' => 4,
                    'Fri' => 5,
                    'Sat' => 6,
                ];

                $validDays = collect($selectedDays)
                    ->filter(fn($value) => $value == 1)
                    ->keys()
                    ->map(fn($day) => $daysOfWeek[$day]);

                $dates = [];
                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    if ($validDays->contains($date->dayOfWeek)) {
                        $dates[] = $date->toDateString();
                    }
                }


                //-------------------for check if punch_controll insert for given employee , date-----------------

                foreach ($employees as $employeeId) {
                    foreach ($dates as $date) {
                        $dateTime = Carbon::parse("$date $request->time");
                        // $employeeId = $request->employee_id;
                        $employeeDateId = DB::table('employee_date')
                            ->select('employee_date.id as employee_date_id')
                            ->where('employee_date.employee_id', $employeeId)
                            ->where('employee_date.date_stamp', $date)
                            ->groupBy('employee_date.id')
                            ->first();
                        // }

                        if ($employeeDateId) {
                            $employeeDateId = $employeeDateId->employee_date_id; // Extract the ID

                            // Check if punch_control exists
                            $countRaw = DB::table('punch_control')
                                ->select('punch_control.*')
                                ->where('punch_control.employee_date_id', $employeeDateId)
                                ->first();

                            if (!$countRaw) { // Check if record doesn't exist
                                $table_1 = 'punch_control';
                                // Insert new record into punch_control
                                $punchControlInputArr = [
                                    'employee_date_id' => $employeeDateId,
                                    'branch_id' => $request->branch_id,
                                    'department_id' => $request->department_id,
                                    'total_time' => 0,
                                    'actual_total_time' => 0,
                                    'meal_policy_id' => 1,
                                    'overlap' => 1,
                                    'note' => $request->note,
                                    'status' => $request->punch_status,
                                    'created_by' => Auth::user()->id,
                                    'updated_by' => Auth::user()->id,
                                ];
                                $punchControlInsertId = $this->common->commonSave($table_1, $punchControlInputArr);
                            } else {
                                // Calculate total_time
                                $punchControlInsertId = $countRaw->id;
                                $totalTime = DB::table(DB::raw('(SELECT TIMESTAMPDIFF(SECOND, MIN(time_stamp), MAX(time_stamp)) as time_diff 
                                FROM punch 
                                WHERE punch_control_id = ' . $punchControlInsertId . ' 
                                GROUP BY punch_control_id) as time_differences'))
                                    ->select(DB::raw('SEC_TO_TIME(SUM(time_diff)) as total_time'))
                                    ->first();
                                $totalTime = $totalTime->total_time;

                                // Update punch_control
                                DB::table('punch_control')
                                    ->where('id', $punchControlInsertId)
                                    ->update([
                                        'total_time' => $totalTime,
                                        'actual_total_time' => $totalTime,
                                        'updated_by' => Auth::user()->id,
                                        'updated_at' => now(),
                                    ]);
                            }
                        } else {
                            // Handle case where no employee_date record is found
                            return response()->json(['message' => 'Employee Date not found'], 404);
                        }

                        if ($punchControlInsertId) {
                            $table_2 = 'punch';
                            $punchInputArr = [
                                'punch_control_id' => $punchControlInsertId,
                                'station_id' => $request->station_id,
                                'punch_type' => $request->punch_type,
                                'punch_status' => $request->punch_status,
                                'time_stamp' => $dateTime->toDateTimeString(),
                                'original_time_stamp' => $dateTime->toDateTimeString(),
                                'actual_time_stamp' => $dateTime->toDateTimeString(),
                                'transfer' => 1,
                                'longitude' => $request->longitude,
                                'latitude' => $request->latitude,
                                'status' => $request->emp_punch_status,
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ];
                            $punchInsertId = $this->common->commonSave($table_2, $punchInputArr);
                            if ($punchInsertId) {
                                return response()->json(['status' => 'success', 'message' => 'Insert successfully', 'data' => ['id' => $punchInsertId]], 200);
                            } else {
                                return response()->json(['status' => 'error', 'message' => 'error insert punch', 'data' => []], 500);
                            }
                        } else {
                            return response()->json(['status' => 'error', 'message' => 'Failed adding Work Experience', 'data' => []], 500);
                        }
                    }
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeePunch(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([

                    'punch_type' => 'required',
                    'punch_status' => 'required',
                ]);
                $employeeId = $request->employee_id;
                $employeeDateId = DB::table('employee_date')
                    ->select('employee_date.id as employee_date_id')
                    ->where('employee_date.employee_id', $employeeId)
                    ->where('employee_date.date_stamp', $request->date)
                    ->groupBy('employee_date.id')
                    ->first();

                if ($employeeDateId) {
                    $employeeDateId = $employeeDateId->employee_date_id; // Extract the ID

                    // Check if punch_control exists
                    $countRaw = DB::table('punch_control')
                        ->select('punch_control.*')
                        ->where('punch_control.employee_date_id', $employeeDateId)
                        ->first();
                }
                $punchControlInsertId = $countRaw->id;

                $table = 'punch';
                $idColumn = 'id';
                $inputArr = [
                    'punch_control_id' => $punchControlInsertId,
                    'station_id' => $request->station_id,
                    'punch_type' => $request->punch_type,
                    'punch_status' => $request->punch_status,
                    'time_stamp' => $request->time_stamp,
                    'original_time_stamp' => $request->original_time_stamp,
                    'actual_time_stamp' => $request->actual_time_stamp,
                    'transfer' => 1,
                    'longitude' => $request->longitude,
                    'latitude' => $request->latitude,
                    'status' => $request->emp_punch_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Punch updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Punch', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeePunch($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Punch';
        $table = 'punch';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }


    public function getEmployeePunchById($id)
    {

        $idColumn = 'employee_date.employee_id';
        // $table = 'emp_job_history';
        $table = 'punch';
        $fields = ['punch.*', 'employee_date.date_stamp as date', 'emp_employees.name_with_initials'];
        $joinArr = [
            'punch_control' => ['punch_control.id', '=', 'punch.punch_control_id'],
            'employee_date' => ['employee_date.id', '=', 'punch_control.employee_date_id'],
            'emp_employees' => ['emp_employees.id', '=', 'employee_date.employee_id'],

        ];
        // $whereArr = ['employee_date.employee_id' => $idColumn];
        $jobhistory = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);
        return response()->json(['data' => $jobhistory], 200);
    }

    public function getEmployeeList()
    {

        $employees = $this->common->commonGetAll('emp_employees', '*');
        return response()->json([
            'data' => $employees,
        ], 200);
    }


    public function getDropdownData()
    {

        $employees = $this->common->commonGetAll('emp_employees', '*');
        $branches = $this->common->commonGetAll('com_branches', '*');
        $departments = $this->common->commonGetAll('com_departments', '*');
        return response()->json([
            'data' => [
                'employees' => $employees,
                'branches' => $branches,
                'departments' => $departments,
            ]
        ], 200);
    }

    public function getSingleEmployeePunch($id)
    {
        $idColumn = 'punch.id';
        $table = 'punch';
        $fields = ['punch.*', 'punch_control.department_id', 'punch_control.branch_id', 'punch_control.note', 'punch_control.note'];
        $joinArr = [
            'punch_control' => ['punch_control.id', '=', 'punch.punch_control_id'],

        ];
        $employee_punch = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);
        return response()->json(['data' => $employee_punch], 200);
    }
}
