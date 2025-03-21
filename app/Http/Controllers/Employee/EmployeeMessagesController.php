<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Exception;

class EmployeeMessagesController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee messages', ['only' => [
            'index',
            'getAllMessages',
            'getMessagesByControlId',
            'getEmployeeDropdownData',
            'getMessagesBySingleId',
            'getSentMessages',
        ]]);
        $this->middleware('permission:create employee messages', ['only' => ['createSendMessage', 'createReplyMessage']]);
        $this->middleware('permission:update read status', ['only' => ['updateReadStatus']]);
        $this->middleware('permission:delete employee messages', ['only' => ['deleteMessage']]);

        $this->common = new CommonModel();
    }


    //pawanee(2024-11-20)
    public function index()
    {
        return view('employee.messages.index');
    }


    //pawanee(2024-11-20)
    public function getEmployeeDropdownData(){
        $users = $this->common->commonGetAll('emp_employees', '*');
        return response()->json([
            'data' => [
                'users' => $users,
            ]
        ], 200);
    }


    //pawanee(2024-11-20)
    public function createSendMessage(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'users' => 'required',
                    'subject' => 'required|string',
                    'description' => 'required|string',

                ]);


                // Hardcode the type_id for "Email" from object_type table
                $typeId = DB::table('object_type')
                    ->where('type', 'email')
                    ->where('name', 'Email')
                    ->value('id');

                if (!$typeId) {
                    return response()->json(['status' => 'error', 'message' => 'Email type not found in object_type table'], 400);
                }


                $table = 'message_control';
                $inputArr = [
                    'type_id' => $typeId, // Hardcoded as "Email"
                    'subject' => $request->subject,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                // Handle messages
                if ($request->has('users')) {
                    $users = explode(',', $request->users);

                    $table2 = 'messages';
                    $inputArr2 = [
                        'message_control_id' => $insertId,
                        'sender_id' => Auth::user()->id,
                        'description' => $request->description,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                    $messageId = $this->common->commonSave($table2, $inputArr2);

                    $table3 = 'message_users';
                    foreach ($users as $user){
                        $inputArr3 = [
                            'message_id' => $messageId, // Message ID from messages table
                            'receiver_id' => trim($user), // Employee ID
                        ];
                        $this->common->commonSave($table3, $inputArr3);
                    }
                }

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Message Sent successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to send message', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }


    //pawanee(2024-11-20)
    public function getAllMessages()
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


        $receivedConnections = [
            'messages' => [
                'con_fields' => [
                    'messages.*',
                    'messages.id as message_id',
                    'messages.message_control_id',
                    'messages.description as message_description',
                    'messages.created_at as sent_at',
                    'sender.id as sender_id',
                    'sender.work_email as sender_email',
                    'message_users.read_status',
                ],
                'con_where' => [
                    'messages.message_control_id' => 'id',
                    'message_users.receiver_id' => $loggedInUserId
                ],
                'con_joins' => [
                    'emp_employees as sender' => ['sender.id', '=', 'messages.sender_id'],
                    'message_users' => ['message_users.message_id', '=', 'messages.id'],
                ],
                'con_name' => 'message_details',
                'except_deleted' => true,
            ],
        ];


        $receivedWhereArr = [
            ['message_control.created_by', '!=', $loggedInUserId]
        ];

        try {

            $sentMessages = $this->common->commonGetById($loggedInUserId, 'message_control.created_by', $table, $fields, $joinArr);

            $receivedMessages = $this->common->commonGetAll($table, $fields, $joinArr, $receivedWhereArr, false, $receivedConnections);


            return response()->json([
                'sentMessages' => $sentMessages ?? [],
                'receivedMessages' => $receivedMessages ?? []
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching all messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }


    //pawanee(2024-11-20)
    public function getSentMessages()
    {

        $loggedInUserId = Auth::user()->id;

        $idColumn = 'message_control.created_by';
        $table = 'message_control';
        $fields = ['message_control.*', 'object_type.name AS type_name'];
        $joinArr = ['object_type' => ['object_type.id', '=', 'message_control.type_id']];

        $sentMessages = $this->common->commonGetById($loggedInUserId, $idColumn, $table, $fields, $joinArr);
        return response()->json(['data' => $sentMessages], 200);
    }


    //pawanee(2024-11-20)
    public function getReceivedMessages()
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
                    'message_users.read_status',
                ],
                'con_where' => [
                    'messages.message_control_id' => 'id',
                    'message_users.receiver_id' => $loggedInUserId
                ],
                'con_joins' => [
                    'emp_employees as sender' => ['sender.id', '=', 'messages.sender_id'],
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

            $receivedMessages = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, false, $connections);

            return response()->json(['data' => $receivedMessages], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching received messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }


    //pawanee(2024-11-20)
    public function updateReadStatus(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'message_control_id' => 'required|integer|exists:message_control,id',
                ]);

                $loggedInUserId = Auth::id();

                $messageIds = DB::table('messages')
                    ->where('message_control_id', $request->message_control_id)
                    ->where('sender_id', '!=', $loggedInUserId)
                    ->pluck('id');

                // Check if there are any messages
                if ($messageIds->isEmpty()) {
                    return response()->json(['status' => 'error', 'message' => 'No received messages found for the given message_control_id.'], 404);
                }

                // Check if there are any unread messages
                $unreadMessages = DB::table('message_users')
                    ->whereIn('message_id', $messageIds)
                    ->where('receiver_id', $loggedInUserId)
                    ->where('read_status', 0)
                    ->exists();

                if (!$unreadMessages) {
                    return response()->json(['status' => 'success', 'message' => 'All messages are already marked as read.'], 200);
                }

                // Update read_status
                $updatedRows = DB::table('message_users')
                    ->whereIn('message_id', $messageIds)
                    ->where('receiver_id', $loggedInUserId)
                    ->where('read_status', 0)
                    ->update(['read_status' => 1]);

                return response()->json(['status' => 'success', 'message' => 'Messages marked as read', 'updatedRows' => $updatedRows], 200);
            });
        } catch (\Throwable $e) {
            Log::error('Error in updateReadStatus: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }



    //pawanee(2024-11-20)
    public function getMessagesBySingleId($id)
    {
        $idColumn = 'messages.id';
        $table = 'messages';
        $fields = [
            'messages.*',
            'messages.id as message_id',
            'messages.message_control_id',
            'messages.description as message_description',
            'messages.created_at as sent_at',
            'sender.id as sender_id',
            'sender.work_email as sender_email', // Sender email from emp_employees
        ];

        // Joining emp_employees twice: once as sender and once as receiver
        $joinArr = [
            'emp_employees as sender' => ['sender.id', '=', 'messages.sender_id'], // Sender details
        ];

        $connections = [
            'message_control' => [
                'con_fields' => ['id As control_id', 'subject As message_subject', 'type_id'],
                'con_where' => ['message_control.id' => 'message_control_id'],
                'con_joins' => [],
                'con_name' => 'subject_details',
                'except_deleted' => true,
            ],
            'message_users' => [
                'con_fields' => ['receiver.id AS receiver_id', 'receiver.work_email AS receiver_email'],
                'con_where' => ['message_users.message_id' => 'id'],
                'con_joins' => [
                    'emp_employees as receiver' => ['receiver.id', '=', 'message_users.receiver_id'],
                ],
                'con_name' => 'receiver_details',
                'except_deleted' => true,
             ],
        ];


        try {
            $messages = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr, [], true, $connections);

            return response()->json(['data' => $messages], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }



    //pawanee(2024-11-20)
    public function getMessagesByControlId($id)
    {

        $idColumn = 'message_control_id';
        $table = 'messages';
        $fields = [
            'messages.*',
            'messages.id as message_id',
            'messages.message_control_id',
            'messages.description as message_description',
            'messages.created_at as sent_at',
            'sender.id as sender_id',
            'sender.work_email as sender_email',
        ];


        $joinArr = [
            'emp_employees as sender' => ['sender.id', '=', 'messages.sender_id'],
        ];

        $connections = [
            'message_control' => [
                'con_fields' => ['id As control_id', 'subject As message_subject', 'type_id'],
                'con_where' => ['message_control.id' => 'message_control_id'],
                'con_joins' => [],
                'con_name' => 'subject_details',
                'except_deleted' => true,
            ],
            'message_users' => [
                'con_fields' => ['receiver.id AS receiver_id', 'receiver.work_email AS receiver_email'],
                'con_where' => ['message_users.message_id' => 'id'],
                'con_joins' => [
                    'emp_employees as receiver' => ['receiver.id', '=', 'message_users.receiver_id'],
                ],
                'con_name' => 'receiver_details',
                'except_deleted' => true,
             ],
        ];

        try {
            $messages = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr, [], true, $connections);


            return response()->json(['data' => $messages], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }



    //pawanee(2024-11-20)
    public function createReplyMessage(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate the request
                $request->validate([
                    'message_control_id' => 'required',
                    'reply_receivers.*' => 'exists:emp_employees,id',
                    'reply_subject' => 'required',
                    'reply_body' => 'required|string',
                ]);

                // Fetch the existing message control ID to ensure the subject is reused
                $messageControl = DB::table('message_control')
                    ->where('id', $request->message_control_id)
                    ->first();

                if (!$messageControl) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid message control ID.'], 404);
                }


                $table = 'messages';
                $inputArr = [
                    'message_control_id' => $messageControl->id,
                    'sender_id' => Auth::user()->id,
                    'description' => $request->reply_body,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                // Ensure the message was created successfully
                if (!$insertId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create reply.'], 500);
                }

                // Insert into the message_users table for selected receivers
                $table2 = 'message_users';
                foreach ($request->reply_receivers as $replyReceiver) {
                    $inputArr2 = [
                        'message_id' => $insertId, // The new message ID
                        'receiver_id' => $replyReceiver, // Receiver ID
                    ];
                    $this->common->commonSave($table2, $inputArr2);
                }


                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Message Reply Sent successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to reply message', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }



    //pawanee(2024-11-20)
    public function deleteMessage($id)
    {
        try {
            DB::beginTransaction();

            $messageControl = DB::table('message_control')
                ->where('id', $id)
                ->first();


            if (!$messageControl) {
                return response()->json(['status' => 'error', 'message' => 'Message control record not found.'], 404);
            }

            $refId = $messageControl->ref_id;

            $this->common->commonDelete($id,['id' => $id], 'Message Chat', 'message_control');
            $this->common->commonDelete($id, ['message_control_id' => $id], 'Message', 'messages');

            if ($refId) {
                $this->common->commonDelete($refId, ['id' => $refId], 'Request', 'request');
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Data deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting message: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to delete message data.'], 500);
        }
    }



}
