<?php

namespace App\Http\Controllers;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeDashboardController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee dashboard', ['only' => [
            'index',
            'getAllEmployeeCount',
            'getAllLeaveCount',
            'getMessages',
            'getRequests',
            'getLeaveRequest',
            ]]);

        $this->common = new CommonModel();
    }


    // pawanee(2024-12-17)
    public function index()
    {
        return view('employee_dashboard.index');
    }


    // pawanee(2024-02-27)
    public function getAllEmployeeCount()
    {
        $table = 'emp_employees';
        $fields = '*';
        $employee = $this->common->commonGetAll($table, $fields);
        $employeeCount = count($employee); // Get the count of employees
        return response()->json(['data' => $employeeCount], 200);
    }




    public function getAllLeaveCount()
    {
        $table = 'leave_request';
        $fields = ['*'];  // Fix the field selection
        $whereArr = ['status' => 'authorized'];

        // Fetch the data
        $authorizedRequests = $this->common->commonGetAll($table, $fields, [], $whereArr, 'all');
        $leaveCount = count($authorizedRequests);

        // Handle empty results
        if ($authorizedRequests->isEmpty()) {
            return response()->json(['message' => 'No authorized leave requests found'], 404);
        }

        return response()->json(['data' => $leaveCount], 200);
    }



    public function getMessages()
    {
        $loggedInUserId = Auth::id();

        $table = 'message_control';
        $fields = [
            'message_control.*',
            'object_type.name AS type_name'
        ];

        $joinArr = [
            'object_type' => ['object_type.id', '=', 'message_control.type_id'],
        ];

        $connections = [
            'messages' => [
                'con_fields' => [
                    'messages.*',
                    'messages.id as message_id',
                    'messages.message_control_id',
                    'messages.description as message_description',
                    'messages.created_at as sent_at',
                    'sender.id as sender_id',
                    'sender.work_email as sender_email',
                    'message_users.receiver_id',
                    'message_users.read_status',
                ],
                'con_where' => [
                    'messages.message_control_id' => 'id',
                    'message_users.receiver_id' => $loggedInUserId, // Make sure this matches your DB
                ],
                'con_joins' => [
                    'emp_employees as sender' => ['sender.user_id', '=', 'messages.sender_id'],
                    'message_users' => ['message_users.message_id', '=', 'messages.id'],
                ],
                'con_name' => 'message_details',
                'except_deleted' => true,
            ],
        ];

        $whereArr = [
            ['message_control.created_by', '!=', $loggedInUserId]
        ];

        try {
            $unreadMessages = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, false, $connections);

            return response()->json(['data' => $unreadMessages ?? []], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching unread messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }


    // pawanee(2024-12-17)
    public function getRequests()
    {
        try {
            $loggedInUserId = Auth::id();

            $table = 'request';
            $fields = [
                'request.*',
                'object_type.name AS type_name',
                'emp_employees.name_with_initials AS employee_name',
                'user_date.user_id',
            ];

            $joinArr = [
                'object_type' => ['object_type.id', '=', 'request.type_id'],
                'user_date' => ['user_date.id', '=', 'request.user_date_id'],
                'emp_employees' => ['emp_employees.user_id', '=', 'user_date.user_id'],
            ];

            $whereArr = [
                ['user_date.user_id', '=', $loggedInUserId]
            ];

            $requestData = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true);

            return response()->json(['data' => $requestData], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching request data: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }


    public function getLeaveRequest()
    {
        try {
            $loggedInUserId = Auth::id();

            $table = 'leave_request';
            $fields = [
                'leave_request.*',
                'accrual_policy.name AS type_name',
            ];
            $joinArr = [
                'accrual_policy' => ['accrual_policy.id', '=', 'leave_request.accurals_policy_id']
            ];
            $whereArr = [
                ['leave_request.user_id', '=', $loggedInUserId]
            ];

            $leaveRequest = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true);
            return response()->json(['data' => $leaveRequest], 200);

        } catch (\Exception $e) {
            Log::error('error fetching Leave Request data: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'internal server error'], 500);
        }
    }


}
