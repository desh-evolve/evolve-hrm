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

        $this->middleware('permission:view mass punch', ['only' => [
            'index',
            'getAllEmployeeList',
            'getMassPunchById',
            'getSingleMassPunch',
        ]]);
        $this->middleware('permission:create mass punch', ['only' => ['createMassPunch']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('attendance.mass_punch.index');
    }

    public function createMassPunch(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'punch_type' => 'required',
                    'punch_status' => 'required',
                ]);
                $users = json_decode($request->user_ids, true); // Decode as an associative array

                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate);
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
                // Filter the selected days
                $validDays = collect($selectedDays)
                    ->filter(fn($value) => $value == 1) // Only keep days that are selected (value == 1)
                    ->keys() // Get the keys (e.g., 'Mon', 'Tue')
                    ->map(fn($day) => $daysOfWeek[$day]); // Convert day names to Carbon day numbers

                $dates = [];
                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    // Check if the current day of the week is in the valid days
                    if ($validDays->contains($date->dayOfWeek)) {
                        $dates[] = $date->toDateString();
                    }
                }
                //-------------------for check if punch_controll insert for given user , date-----------------
                $insertedPunchIds = [];
                foreach ($users as $user) {
                    foreach ($dates as $date) {
                        $time = $request->time;
                        // Combine and parse date and time
                        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', "$date $time");
                        // $userId = $request->user_id;
                        $userDateId = DB::table('user_date')
                            ->select('user_date.id as user_date_id')
                            ->where('user_date.user_id', $user)
                            ->where('user_date.date_stamp', $date)
                            ->groupBy('user_date.id')
                            ->first();

                        $userName = DB::table('emp_employees')
                            ->select('emp_employees.name_with_initials as name')
                            ->where('emp_employees.user_id', $user)
                            ->first();

                        if ($userDateId) {
                            $userDateId = $userDateId->user_date_id; // Extract the ID

                            // Check if punch_control exists
                            $countRaw = DB::table('punch_control')
                                ->select('punch_control.*')
                                ->where('punch_control.user_date_id', $userDateId)
                                ->first();

                            if (!$countRaw) { // Check if record doesn't exist
                                $table_1 = 'punch_control';
                                // Insert new record into punch_control
                                $punchControlInputArr = [
                                    'user_date_id' => $userDateId,
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
                            // Handle case where no user_date record is found
                            return response()->json(['message' => 'Employee Date not found'], 404);
                        }

                        if ($punchControlInsertId) {
                            $table_2 = 'punch';

                            $existingPunch = DB::table($table_2)
                                ->where('punch_control_id', $punchControlInsertId)
                                ->where('punch_status', $request->punch_status)
                                ->where('punch_type', $request->punch_type)
                                ->where('time_stamp', $dateTime->toDateTimeString())
                                ->first();

                            // If a record exists, skip to the next iteration
                            if ($existingPunch) {
                                continue;
                            }

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
                            // dd('$punchInputArr',$punchInputArr);
                            $punchInsertId = $this->common->commonSave($table_2, $punchInputArr);
                            if ($punchInsertId) {
                                $insertedPunchIds[] = [
                                    'punch_type' => $punchInputArr['punch_type'],
                                    'punch_status' => $punchInputArr['punch_status'],
                                    'time_stamp' => $punchInputArr['time_stamp'],
                                    'emp_name' => $userName,
                                ];
                            } else {
                                var_dump('Error inserting punch for:', $user, $date);
                            }
                        } else {
                            return response()->json(['status' => 'error', 'message' => 'Failed adding Work Experience', 'data' => []], 500);
                        }
                    }
                }
                if (!empty($insertedPunchIds)) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Processed successfully',
                        'data' => ['insertedPunchIds' => $insertedPunchIds]
                    ], 200);
                } else {
                    // return response()->json(['message' => 'Already Punch'], 200);
                    return response()->json(['status' => 'success','message' => 'Already Punch','data' => []], 200);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function showMassPunchList(Request $request)
    {
        // Get the 'data' query parameter
        $insertedPunchIds = json_decode($request->query('data'), true);

        // Pass it to the view
        return view('attendance.mass_punch.mass_punch_list', compact('insertedPunchIds'));
    }
    // public function updateMassPunch(Request $request, $id)
    // {
    //     try {
    //         return DB::transaction(function () use ($request, $id) {
    //             $request->validate([

    //                 'punch_type' => 'required',
    //                 'punch_status' => 'required',
    //             ]);
    //             $userId = $request->user_id;
    //             $userDateId = DB::table('user_date')
    //                 ->select('user_date.id as user_date_id')
    //                 ->where('user_date.user_id', $userId)
    //                 ->where('user_date.date_stamp', $request->date)
    //                 ->groupBy('user_date.id')
    //                 ->first();

    //             if ($userDateId) {
    //                 $userDateId = $userDateId->user_date_id; // Extract the ID

    //                 // Check if punch_control exists
    //                 $countRaw = DB::table('punch_control')
    //                     ->select('punch_control.*')
    //                     ->where('punch_control.user_date_id', $userDateId)
    //                     ->first();
    //             }
    //             $punchControlInsertId = $countRaw->id;

    //             $table = 'punch';
    //             $idColumn = 'id';
    //             $inputArr = [
    //                 'punch_control_id' => $punchControlInsertId,
    //                 'station_id' => $request->station_id,
    //                 'punch_type' => $request->punch_type,
    //                 'punch_status' => $request->punch_status,
    //                 'time_stamp' => $request->time_stamp,
    //                 'original_time_stamp' => $request->original_time_stamp,
    //                 'actual_time_stamp' => $request->actual_time_stamp,
    //                 'transfer' => 1,
    //                 'longitude' => $request->longitude,
    //                 'latitude' => $request->latitude,
    //                 'status' => $request->emp_punch_status,
    //                 'updated_by' => Auth::user()->id,

    //             ];
    //             $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

    //             if ($insertId) {
    //                 return response()->json(['status' => 'success', 'message' => 'Punch updated successfully', 'data' => ['id' => $insertId]], 200);
    //             } else {
    //                 return response()->json(['status' => 'error', 'message' => 'Failed updating Punch', 'data' => []], 500);
    //             }
    //         });
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
    //     }
    // }

    // public function deleteMassPunch($id)
    // {
    //     $whereArr = ['id' => $id];
    //     $title = 'Employee Punch';
    //     $table = 'punch';

    //     return $this->common->commonDelete($id, $whereArr, $title, $table);
    // }


    // public function getMassPunchById($id)
    // {

    //     $idColumn = 'user_date.user_id';
    //     // $table = 'emp_job_history';
    //     $table = 'punch';
    //     $fields = ['punch.*', 'user_date.date_stamp as date', 'emp_employees.name_with_initials'];
    //     $joinArr = [
    //         'punch_control' => ['punch_control.id', '=', 'punch.punch_control_id'],
    //         'user_date' => ['user_date.id', '=', 'punch_control.user_date_id'],
    //         'emp_employees' => ['emp_employees.user_id', '=', 'user_date.user_id'],

    //     ];
    //     // $whereArr = ['user_date.user_id' => $idColumn];
    //     $jobhistory = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);
    //     return response()->json(['data' => $jobhistory], 200);
    // }

    public function getEmployeeList()
    {

        $users = $this->common->commonGetAll('emp_employees', '*');
        return response()->json([
            'data' => $users,
        ], 200);
    }


    public function getDropdownData()
    {

        $users = $this->common->commonGetAll('emp_employees', ['id', 'user_id', 'name_with_initials AS name']);
        $branches = $this->common->commonGetAll('com_branches', '*');
        $departments = $this->common->commonGetAll('com_departments', '*');
        return response()->json([
            'data' => [
                'users' => $users,
                'branches' => $branches,
                'departments' => $departments,
            ]
        ], 200);
    }

    // public function getSingleMassPunch($id)
    // {
    //     $idColumn = 'punch.id';
    //     $table = 'punch';
    //     $fields = ['punch.*', 'punch_control.department_id', 'punch_control.branch_id', 'punch_control.note', 'punch_control.note'];
    //     $joinArr = [
    //         'punch_control' => ['punch_control.id', '=', 'punch.punch_control_id'],

    //     ];
    //     $employee_punch = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);
    //     return response()->json(['data' => $employee_punch], 200);
    // }
}
