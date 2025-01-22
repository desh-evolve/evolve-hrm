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
            'getAllLeaveCount',
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
        $table = 'request';
        $fields = '*';
        $whereArr = ['status' => 'authorized'];
        $authorizedRequests = $this->common->commonGetAll($table, $fields, [], $whereArr);
        return response()->json(['data' => $authorizedRequests], 200);
    }




    // pawanee(2024-12-17)
    public function getNewMessages()
    {
        $loggedInUserId = Auth::user()->id;

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
                    'message_employees.read_status',
                ],
                'con_where' => [
                    'messages.message_control_id' => 'id',
                    'message_employees.received_id' => $loggedInUserId
                ],
                'con_joins' => [
                    'emp_employees as sender' => ['sender.user_id', '=', 'messages.sender_id'],
                    'message_employees' => ['message_employees.message_id', '=', 'messages.id'],
                ],
                'con_name' => 'message_details',
                'except_deleted' => true,
            ],
        ];

        $whereArr = [
            ['message_control.created_by', '!=', $loggedInUserId]
        ];

        try {

            $receivedMessages = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, false, $connections);

            return response()->json(['data' => $receivedMessages], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching received messages: ' . $e->getMessage());
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
                'emp_employees' => ['emp_employees.user_id', '=', 'employee_date.employee_id'],
            ];

            $requestData = $this->common->commonGetAll($table, $fields, $joinArr, [], true);

            return response()->json(['data' => $requestData], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching request data: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }


}
