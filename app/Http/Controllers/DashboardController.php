<?php

namespace App\Http\Controllers;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view dashboard', ['only' => [
            'index',
            'getAllEmployeeCount',
            'getNewMessages',
            'getRequestsData'
            ]]);

        $this->common = new CommonModel();
    }


    // pawanee(2024-12-17)
    public function index()
    {
        return view('dashboard.index');
    }


    // pawanee(2024-12-17)
    public function getAllEmployeeCount()
    {
        $table = 'emp_employees';
        $fields = '*';
        $employee = $this->common->commonGetAll($table, $fields);
        $employeeCount = count($employee); // Get the count of employees
        return response()->json(['data' => $employeeCount], 200);
    }


    // pawanee(2024-12-17)
    public function getAllLeaveCount()
    {
        $table = 'leaves';
        $fields = '*';
        $leaves = $this->common->commonGetAll($table, $fields);
        $leaveCount = count($leaves); // Get the count of approved Leaves
        return response()->json(['data' => $leaveCount], 200);
    }


    // public function getEmployeeStats()
    // {
    //     try {
    //         // Get total employee count from the emp_employees table
    //         $table = 'emp_employees';
    //         $fields = '*';
    //         $employees = $this->common->commonGetAll($table, $fields);
    //         $employeeCount = count($employees); // Count total employees

    //         // Get total approved leaves from the leaves table
    //         $leavesTable = 'leaves';
    //         $approvedLeaves = $this->common->commonGetAll($leavesTable, $fields);
    //         $approvedLeavesCount = count(array_filter($approvedLeaves, function ($leave) {
    //             return isset($leave['status']) && $leave['status'] === 'approved';
    //         }));


    //         return response()->json([
    //             'data' => [
    //                 'total_employees' => $employeeCount,
    //                 'approved_leaves' => $approvedLeavesCount,
    //             ]], 200);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Unable to fetch data', 'message' => $e->getMessage()], 500);
    //     }

    // }


    // public function getNewMessages()
    // {
    //     $loggedInUserId = Auth::user()->id; // Get the logged-in user's ID

    //     $whereArr = [['message_control.created_by', '!=', $loggedInUserId]]; // Exclude messages sent by the logged-in user
    //     $table = 'message_control';
    //     $fields = ['message_control.*', 'object_type.name AS type_name'];
    //     $joinArr = ['object_type' => ['object_type.id', '=', 'message_control.type_id']];

    //     $receivedMessages = $this->common->commonGetById($loggedInUserId, $whereArr, $table, $fields, $joinArr);
    //     return response()->json(['data' => $receivedMessages], 200);
    // }



    // pawanee(2024-12-17)
    public function getNewMessages()
    {
        $loggedInUserId = Auth::user()->id;

        $whereArr = [['message_control.created_by', '!=', $loggedInUserId]];
        $table = 'message_control';
        $fields = [
            'message_control.*',
            'object_type.name AS type_name',
            'messages.sender_id',
            'emp_employees.name_with_initials AS sender_name'
        ];
        $joinArr = [
            'object_type' => ['object_type.id', '=', 'message_control.type_id'],
            'messages' => ['messages.message_control_id', '=', 'message_control.id'],
            'emp_employees' => ['emp_employees.id', '=', 'messages.sender_id']
        ];

        try {

            $receivedMessages = $this->common->commonGetById($loggedInUserId, $whereArr, $table, $fields, $joinArr);

            return response()->json(['data' => $receivedMessages], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }


    // pawanee(2024-12-17)
    public function getRequestsData()
    {
        try {
            $table = 'request';
            $fields = [
                'request.*',
                'object_type.name AS type_name',
                'emp_employees.name_with_initials AS employee_name',
                'employee_date.employee_id',
            ];

            $joinArr = [
                'object_type' => ['object_type.id', '=', 'request.type_id'],
                'employee_date' => ['employee_date.id', '=', 'request.employee_date_id'],
                'emp_employees' => ['emp_employees.id', '=', 'employee_date.employee_id'],
            ];

            $requestData = $this->common->commonGetAll($table, $fields, $joinArr, [], true);

            return response()->json(['data' => $requestData], 200);
            
        } catch (\Exception $e) {
            Log::error('Error fetching request data: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }


}
