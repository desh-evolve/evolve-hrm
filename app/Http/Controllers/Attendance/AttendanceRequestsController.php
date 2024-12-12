<?php

namespace App\Http\Controllers\Attendance;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceRequestsController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view attendance requests', ['only' => [
            'index',
            'getAllAttendenceRequests',
            'getRequestsByControlId',
            'getEmployeeDropdownData',

        ]]);
        $this->middleware('permission:create attendance requests', ['only' => ['createAttendenceRequests']]);
        $this->middleware('permission:delete attendance requests', ['only' => ['deleteAttendenceRequests']]);

        $this->common = new CommonModel();
    }


    //pawanee(2024-12-09)
    public function index()
    {
        return view('attendance.requests.index');
    }


    //pawanee(2024-12-09)
    public function getRequestDropdownData(){
        $employees = $this->common->commonGetAll('emp_employees', '*');
        $types = $this->common->commonGetAll(
            'object_type',
            [
                'object_type.id as id',
                'object_type.type as type_category',
                'object_type.name as type_name',
            ]);

        return response()->json([
            'data' => [
                'employees' => $employees,
                'types' => $types,
            ]
        ], 200);
    }



    //pawanee(2024-12-09)
    public function getAllAttendenceRequests()
    {
       //
    }


    //pawanee(2024-12-09)
    public function getRequestsByControlId($id)
    {
        $idColumn = 'employee_id';
        $table = 'request';
        $fields = ['request.*','branch_name', 'department_name', 'emp_designation_name'];
        $joinArr = [
            'com_branches'=>['com_branches.id', '=', 'emp_job_history.branch_id'],
            'com_departments'=>['com_departments.id', '=', 'emp_job_history.department_id'],
            'com_employee_designations'=>['com_employee_designations.id', '=', 'emp_job_history.designation_id'],

        ];
        $request = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);
        return response()->json(['data' => $request], 200);
    }


    //pawanee(2024-12-09)
    public function createAttendenceRequests(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'employee_id' => 'required',
                    'type_id' => 'required',
                    'employee_date_id' => 'required',
                    'description' => 'required|string',
                ]);


                    // Check if employee_id and date match in employee_date table
                    $employeeDate = DB::table('employee_date')
                    ->where('employee_id', $request->employee_id)
                    ->where('date_stamp', $request->employee_date_id)
                    ->first();

                if (!$employeeDate) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No matching record found for the employee and date.',
                    ], 200);
                }

                $table = 'request';
                $inputArr = [
                    'employee_date_id' => $employeeDate->id, // ID from employee_date table
                    'type_id' => $request->type_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);


                // Insert into message_control table
                $typeData = DB::table('object_type')
                    ->where('id', $request->type_id)
                    ->first();

                if (!$typeData) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid Type ID provided.',
                    ], 200);
                }

                $table2 = 'message_control';
                $inputArr2 = [
                    'type_id' => $typeData->id,
                    'subject' => $typeData->name,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId2 = $this->common->commonSave($table2, $inputArr2);

                // Handle messages
                if ($insertId2) {

                    $table3 = 'messages';
                    $inputArr3 = [
                        'message_control_id' => $insertId2,
                        'sender_id' => Auth::user()->id,
                        'description' => $request->description,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                    $messageId = $this->common->commonSave($table3, $inputArr3);

                    // If employees are provided, handle message_employees
                    if ($messageId) {

                        $table4 = 'message_employees';
                        $inputArr4 = [
                            'message_id' => $messageId, // Message ID from messages table
                            'received_id' => $request->employee_id, // Employee ID
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ];
                        $this->common->commonSave($table4, $inputArr4);
                    }
                }
                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Request Sent successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to Sent Request', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }


    //pawanee(2024-12-09)
    public function deleteAttendenceRequests($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Attendance Request';
        $table = 'request';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }



}
