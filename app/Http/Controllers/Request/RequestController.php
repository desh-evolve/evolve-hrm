<?php

namespace App\Http\Controllers\Request;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{

    // not complete


    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view attendance requests', ['only' => [
            'index',
            'getRequestsByControlId',
            'getRequestDropdownData',

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
        $users = $this->common->commonGetAll('emp_employees', '*');
        $types = $this->common->commonGetAll(
            'object_type',
            [
                'object_type.id as id',
                'object_type.type as type_category',
                'object_type.name as type_name',
            ]);

        return response()->json([
            'data' => [
                'users' => $users,
                'types' => $types,
            ]
        ], 200);
    }


    //pawanee(2024-12-09)
    public function getRequestsByControlId($userId)
    {
        try {
            // Get all `user_date_id`s for the given `employee_id`
            $employeeDateIds = DB::table('user_date')
                ->where('user_id', $userId)
                ->pluck('id');

            if ($employeeDateIds->isEmpty()) {
                return response()->json(['error' => 'No matching user date records found for the given user_id'], 404);
            }

            $idColumn = 'user_date_id';
            $table = 'request';
            $fields = [
                'request.*',
                'object_type.name as type_name',
            ];

            $joinArr = [
                'object_type' => ['object_type.id', '=', 'request.type_id']
            ];

            $connections = [
                'message_control' => [
                    'con_fields' => ['message_control.id', 'messages.description AS request_status'],
                    'con_where' => ['message_control.ref_id' => 'id'],
                    'con_joins' => [
                        'messages' => ['messages.message_control_id', '=', 'message_control.id'],
                    ],
                    'con_name' => 'status_details',
                    'except_deleted' => true,
                ],
                'user_date' => [
                    'con_fields' => ['date_stamp'],
                    'con_where' => ['user_date.id' => 'user_date_id'],
                    'con_joins' => [],
                    'con_name' => 'date_details',
                    'except_deleted' => true,
                ],
            ];


            $attRequest = [];

            foreach ($employeeDateIds as $employeeDateId) {
                $result = $this->common->commonGetById($employeeDateId, $idColumn, $table, $fields, $joinArr, [], true, $connections);
                if ($result) {
                    $attRequest[] = $result;
                }
            }

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
                    'user_id' => 'required',
                    'type_id' => 'required',
                    'user_date_id' => 'required',
                    'description' => 'required|string',
                ]);


                    // Check if user_id and date match in user_date table
                    $userDate = DB::table('user_date')
                    ->where('user_id', $request->user_id)
                    ->where('date_stamp', $request->user_date_id)
                    ->first();

                if (!$userDate) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No matching record found for the user and date.',
                    ], 200);
                }

                $table = 'request';
                $inputArr = [
                    'user_date_id' => $userDate->id, // ID from user_date table
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


                // handle message_users
                if ($messageId) {

                    $table4 = 'message_users';
                    $inputArr4 = [
                        'message_id' => $messageId, // Message ID from messages table
                        'receiver_id' => $request->user_id,
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
        $res = $this->common->commonDelete($id, ['id' => $id], 'Request Status', 'request');
        $this->common->commonDelete($id, ['ref_id' => $id], 'Request Status', 'message_control');

        return $res;
    }

    public function getSumByPayPeriodIdAndStatus($pay_period_id, $status){

        $table = 'request';
        $fields = [DB::raw('user_date.pay_period_id as pay_period_id, count(*) as total')];
        $joinArr = [
           'user_date' => ['user_date.id', '=', 'request.user_date_id'] 
        ];
        
        $whereArr = [
            ['request.status', '=', '"'.$status.'"'],
            'user_date.pay_period_id in ('.$pay_period_id.')'
        ];

        $exceptDel = true;
        $connections = [];
        $groupBy = 'pay_period_id';
        $orderBy = null;

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy);
        
        return $res;

    }

}
