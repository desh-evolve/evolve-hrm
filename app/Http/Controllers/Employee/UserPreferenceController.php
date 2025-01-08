<?php

namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class UserPreferenceController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view user preference', ['only' => [
            'index',
            'getAllUserPreference',
            'getUserPreferenceById',
            'getUserPreferenceDropdownData',
        ]]);
        $this->middleware('permission:create user preference', ['only' => ['createUserPreference']]);
        $this->middleware('permission:update user preference', ['only' => ['updateUserPreference']]);
        $this->middleware('permission:delete user preference', ['only' => ['deleteUserPreference']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('employee.user_preference');
    }
    public function getUserPreferenceDropdownData()
    {
        $time_zone_list = $this->common->commonGetAll('time_zones', '*');

        //type => create table
        $start_week_on_day_list = [
            ['id' => 1, 'name' => 'Monday'],
            ['id' => 2, 'name' => 'Tuesday'],
            ['id' => 3, 'name' => 'Wednesday'],
            ['id' => 4, 'name' => 'Thursday'],
            ['id' => 5, 'name' => 'Friday'],
            ['id' => 6, 'name' => 'Saturday'],
            ['id' => 7, 'name' => 'Sunday'],
        ];
        //type => create table
        $language_list = [
            ['id' => 1, 'name' => 'English', 'value' => 'english'],
            ['id' => 2, 'name' => 'Spanish (UO)', 'value' => 'spanish'],
            ['id' => 3, 'name' => 'French (UO)', 'value' => 'french'],
            ['id' => 4, 'name' => 'German (UO)', 'value' => 'german'],
            ['id' => 5, 'name' => 'Italian (UO)', 'value' => 'italian'],
            ['id' => 6, 'name' => 'Portuguese (UO)', 'value' => 'portuguese'],
            ['id' => 7, 'name' => 'Danish (UO)', 'value' => 'danish'],
            ['id' => 8, 'name' => 'Chinese (UO)', 'value' => 'chinese'],
        ];
      

        return response()->json([
            'data' => [
                'language_list' => $language_list,
                'start_week_on_day_list' => $start_week_on_day_list,
                'time_zone_list' => $time_zone_list,
            ]
        ], 200);
    }
    public function createUserPreference(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // $request->validate([
                //     'user_id' => 'required',
                //     'date_format' => 'required',
                //     'time_format' => 'required',
                //     'time_unit_format' => 'required',
                //     'time_zone' => 'required',
                //     'items_per_page' => 'required',
                //     'timesheet_view' => 'required',
                //     'start_week_day' => 'required|integer',
                //     'language' => 'nullable|string',
                //     'enable_email_notification_exception' => 'required',
                //     'enable_email_notification_message' => 'required',
                //     'enable_email_notification_home' => 'required',
                //     'user_preference_status' => 'required',
                // ]);

                $table = 'user_preference';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'date_format' => $request->date_format,
                    'time_format' => $request->time_format,
                    'time_unit_format' => $request->time_unit_format,
                    'time_zone' => $request->time_zone,
                    'items_per_page' => 25,
                    'timesheet_view' => 1,
                    'start_week_day' => $request->start_week_day,
                    'language' => $request->language ?: "",
                    'enable_email_notification_exception' => $request->enable_email_notification_exception,
                    'enable_email_notification_message' => $request->enable_email_notification_message,
                    'enable_email_notification_home' => $request->enable_email_notification_home,
                    'status' => $request->user_preference_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'User Preference added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding User Preference', 'data' => []], 500);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateUserPreference(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'date_format' => 'required',
                    'time_format' => 'required',
                    'time_unit_format' => 'required',
                    'time_zone' => 'required',
                    'items_per_page' => 'required',
                    'timesheet_view' => 'required',
                    'start_week_day' => 'required|integer',
                    'language' => 'nullable|string',
                    'enable_email_notification_exception' => 'required',
                    'enable_email_notification_message' => 'required',
                    'enable_email_notification_home' => 'required',
                    'status' => 'required',
                ]);

                $table = 'user_preference';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' => 1,
                    'date_format' => $request->date_format,
                    'time_format' => $request->time_format,
                    'time_unit_format' => $request->time_unit_format,
                    'time_zone' => $request->time_zone,
                    'items_per_page' => $request->items_per_page,
                    'timesheet_view' => $request->timesheet_view,
                    'start_week_day' => $request->start_week_day,
                    'language' => $request->language ?: "",
                    'enable_email_notification_exception' => $request->enable_email_notification_exception,
                    'enable_email_notification_message' => $request->enable_email_notification_message,
                    'enable_email_notification_home' => $request->enable_email_notification_home,
                    'status' => $request->user_preference_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'User Preference updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating User Preference', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteUserPreference($id)
    {
        $whereArr = ['id' => $id];
        $title = 'User Preference';
        $table = 'user_preference';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllUserPreference()
    {
        $table = 'user_preference';
        $fields = '*';
        $user_preference = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $user_preference], 200);
    }

    public function getUserPreferenceById($id)
    {
        $idColumn = 'id';
        $table = 'user_preference';
        $fields = '*';
        $user_preference = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_preference], 200);
    }
}
