<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class JobHistoryController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view user job history', ['only' => [
            'index',
            'getJobHistoryByEmployeeId',
            'getJobHistoryDropdownData',
            'getJobHistoryBySingleEmployee',
        ]]);
        $this->middleware('permission:create user job history', ['only' => ['createJobHistory']]);
        $this->middleware('permission:update user job history', ['only' => ['updateJobHistory']]);
        $this->middleware('permission:delete user job history', ['only' => ['deleteJobHistory']]);

        $this->common = new CommonModel();
    }


    //pawanee(2024-10-28)
    public function index()
    {
        return view('user.job_history');
    }


    //pawanee(2024-10-28)
    public function getJobHistoryDropdownData(){
        $users = $this->common->commonGetAll('emp_employees', '*');
        $branches = $this->common->commonGetAll('com_branches', '*');
        $departments = $this->common->commonGetAll('com_departments', '*');
        $designations = $this->common->commonGetAll('com_employee_designations', '*');
        return response()->json([
            'data' => [
                'users' => $users,
                'branches' => $branches,
                'departments' => $departments,
                'designations' => $designations,
            ]
        ], 200);
    }



    //pawanee(2024-10-28)
    public function createJobHistory(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'user_id' => 'required',
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'designation_id' => 'required',
                    'first_worked_date' => 'required|date',
                    'last_worked_date' => 'required|date',
                    'note' => 'required',
                ]);

                $table = 'emp_job_history';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'branch_id' => $request->branch_id,
                    'department_id' => $request->department_id,
                    'designation_id' => $request->designation_id,
                    'first_worked_date' => $request->first_worked_date,
                    'last_worked_date' => $request->last_worked_date,
                    'note' => $request->note,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Job History added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add Job History', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }


    //pawanee(2024-10-28)
    public function updateJobHistory(Request $request, $id){
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'designation_id' => 'required',
                    'first_worked_date' => 'required|date',
                    'last_worked_date' => 'required|date',
                    'note' => 'required',
                ]);

                $table = 'emp_job_history';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'branch_id' => $request->branch_id,
                    'department_id' => $request->department_id,
                    'designation_id' => $request->designation_id,
                    'first_worked_date' => $request->first_worked_date,
                    'last_worked_date' => $request->last_worked_date,
                    'note' => $request->note,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Job History updateded successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update Job History', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }



    //pawanee(2024-10-28)
    public function deleteJobHistory($id){
        $whereArr = ['id' => $id];
        $title = 'Employee Job History';
        $table = 'emp_job_history';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }



    //pawanee(2024-10-28)
    public function getJobHistoryByEmployeeId($id){
        $idColumn = 'user_id';
        $table = 'emp_job_history';
        $fields = ['emp_job_history.*','branch_name', 'department_name', 'emp_designation_name'];
        $joinArr = [
            'com_branches'=>['com_branches.id', '=', 'emp_job_history.branch_id'],
            'com_departments'=>['com_departments.id', '=', 'emp_job_history.department_id'],
            'com_employee_designations'=>['com_employee_designations.id', '=', 'emp_job_history.designation_id'],

        ];
        $jobhistory = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr);
        return response()->json(['data' => $jobhistory], 200);
    }


    //pawanee(2024-10-28)
    public function getJobHistoryBySingleEmployee($id)
    {
        $idColumn = 'id';
        $table = 'emp_job_history';
        $fields = '*';
        $user_qualification = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_qualification], 200);
    }



    
}
