<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeeQualificationController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view user qualification', ['only' => [
            'index',
            'getAllEmployeeQualification',
            'getAllEmployeeList',
            'getEmployeeQualificationById',
            'getSingleEmployeeQualification',
        ]]);
        $this->middleware('permission:create user qualification', ['only' => ['createEmployeeQualification']]);
        $this->middleware('permission:update user qualification', ['only' => ['updateEmployeeQualification']]);
        $this->middleware('permission:delete user qualification', ['only' => ['deleteEmployeeQualification']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('user_qualification.index');
    }

    public function createEmployeeQualification(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'user_id' => 'required',
                    'qualification' => 'required',
                    'institute' => 'required',
                    'year' => 'required',
                    'qualification_status' => 'required',
                ]);

                $table = 'emp_qualifications';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'qualification' => $request->qualification,
                    'institute' => $request->institute,
                    'remarks' => $request->remarks,
                    'year' => $request->year,
                    'status' => $request->qualification_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Qualification  added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Qualification', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeeQualification(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'qualification' => 'required',
                    'institute' => 'required',
                    'year' => 'required',
                    'qualification_status' => 'required',
                ]);

                $table = 'emp_qualifications';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'qualification' => $request->qualification,
                    'institute' => $request->institute,
                    'year' => $request->year,
                    'remarks' => $request->remarks,
                    'status' => $request->qualification_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Qualification updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Qualification', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeeQualification($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Qualifications';
        $table = 'emp_qualifications';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeeQualification()
    {
        $table = 'emp_qualifications';
        $fields = '*';
        $user_qualifications = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $user_qualifications], 200);
    }

    public function getEmployeeQualificationById($id)
    {
        $idColumn = 'user_id';
        $table = 'emp_qualifications';
        $fields = '*';
        $user_qualifications = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_qualifications], 200);
    }
    public function getEmployeeList()
    {

        $users = $this->common->commonGetAll('emp_employees', '*');
        // $users = $this->common->commonGetAll($table, $fields);
        return response()->json([
            // 'data' => [
            //     'users' => $users,
            // ]
            'data' => $users,
        ], 200);
    }

    public function getSingleEmployeeQualification($id)
    {
        $idColumn = 'id';
        $table = 'emp_qualifications';
        $fields = '*';
        $user_qualification = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_qualification], 200);
    }
}
