<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeeWorkExperienceController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee work experience', ['only' => [
            'index',
            'getAllEmployeeWorkExperience',
            'getAllEmployeeList',
            'getEmployeeWorkExperienceById',
            'getSingleEmployeeWorkExperience',
        ]]);
        $this->middleware('permission:create employee work experience', ['only' => ['createEmployeeWorkExperience']]);
        $this->middleware('permission:update employee work experience', ['only' => ['updateEmployeeWorkExperience']]);
        $this->middleware('permission:delete employee work experience', ['only' => ['deleteEmployeeWorkExperience']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('employee_work_experience.index');
    }

    public function createEmployeeWorkExperience(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'user_id' => 'required',
                    'company' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    'department' => 'required',
                    'designation' => 'required',
                    'remarks' => 'required',
                ]);

                $table = 'emp_work_experience';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'company' => $request->company,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'department' => $request->department,
                    'designation' => $request->designation,
                    'remarks' => $request->remarks,
                    'status' => $request->work_experience_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Work Experience  added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Work Experience', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeeWorkExperience(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'company' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    'department' => 'required',
                    'designation' => 'required',
                    'remarks' => 'required',
                ]);

                $table = 'emp_work_experience';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'company' => $request->company,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'department' => $request->department,
                    'designation' => $request->designation,
                    'remarks' => $request->remarks,
                    'status' => $request->work_experience_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Work Experience updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Work Experience', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeeWorkExperience($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Work Experience';
        $table = 'emp_work_experience';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeeWorkExperience()
    {
        $table = 'emp_work_experience';
        $fields = '*';
        $user_work_experience = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $user_work_experience], 200);
    }

    public function getEmployeeWorkExperienceById($id)
    {
        $idColumn = 'user_id';
        $table = 'emp_work_experience';
        $fields = '*';
        $user_work_experience = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_work_experience], 200);
    }

    
    public function getEmployeeList()
    {

        $users = $this->common->commonGetAll('emp_employees', '*');
        return response()->json([
            'data' => $users,
        ], 200);
    }

    public function getSingleEmployeeWorkExperience($id)
    {
        $idColumn = 'id';
        $table = 'emp_work_experience';
        $fields = '*';
        $user_work_experience = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_work_experience], 200);
    }
}
