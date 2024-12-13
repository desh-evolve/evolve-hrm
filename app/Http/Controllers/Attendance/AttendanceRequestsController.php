<?php

namespace App\Http\Controllers\Attendance;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceRequestsController extends Controller
{

    // not complete 


    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view attendance requests', ['only' => [
            'index',
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
    public function getRequestsByControlId($employeeId)
    {
        // Get the `id` from the `employee_date` table where `employee_id`
        $employeeDateId = DB::table('employee_date')
            ->where('employee_id', $employeeId)
            ->value('id');

        if (!$employeeDateId) {
            return response()->json(['error' => 'No matching employee_date record found for the given employee_id'], 404);
        }

        $idColumn = 'employee_date_id';
        $table = 'request';
        $fields = [
            'request.*',
            'object_type.name as type_name',
        ];

        $joinArr = [
            'object_type'=>['object_type.id', '=', 'request.type_id']
        ];

        $connections = [
            'message_control' => [
                'con_fields' => ['message_control.id As control_id', 'messages.description AS request_status'],
                'con_where' => ['message_control.ref_id' => 'id'], // Match request.id with message_control.request_id
                'con_joins' => [
                    'messages' => ['messages.message_control_id', '=', 'message_control.id'],
                ],
                'con_name' => 'status_details',
                'except_deleted' => true,
            ],
            'employee_date' => [
                'con_fields' => ['date_stamp'],
                'con_where' => ['employee_date.id' => 'employee_date_id'],
                'con_joins' => [],
                'con_name' => 'date_details',
                'except_deleted' => true,
            ],
        ];


        try {
            $attRequest = $this->common->commonGetById($employeeDateId, $idColumn, $table, $fields, $joinArr, [], true, $connections);

            return response()->json(['data' => $attRequest], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }

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
                    return response()->json(['status' => 'error', 'message' => 'Invalid Type ID provided.',], 200);
                }

                //ref type
                $refType = 'request';


                if ($insertId) {

                    $table2 = 'message_control';
                    $inputArr2 = [
                        'type_id' => $typeData->id,
                        'subject' => $typeData->name,
                        'ref_type' => $refType, //ref type
                        'ref_id' => $insertId, // Request ID from request table
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];

                    $insertId2 = $this->common->commonSave($table2, $inputArr2);
                }


                // Handle messages
                if ($insertId2) {

                    $table3 = 'messages';
                    $inputArr3 = [
                        'message_control_id' => $insertId2, // Message control ID from message_control table
                        'sender_id' => Auth::user()->id,
                        'description' => $request->description,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                    $messageId = $this->common->commonSave($table3, $inputArr3);
                }


                // handle message_employees
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
        $title = 'Request Status';
        $table = 'request';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }



}
