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
        $this->middleware('permission:view employee qualification', ['only' => [
            'index',
            // 'employee_form',
            'getAllEmployeeQualification',
            'getEmployeeQualificationById',
        ]]);
        $this->middleware('permission:create employee qualification', ['only' => ['createEmployeeQualification']]);
        $this->middleware('permission:update employee qualification', ['only' => ['updateEmployeeQualification']]);
        $this->middleware('permission:delete employee qualification', ['only' => ['deleteEmployeeQualification']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('employee_qualification.index');
    }

    public function createEmployeeQualification(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'employee_id' => 'required',
                    'qualification' => 'required',
                    'institute' => 'required',
                    'year' => 'required',
                ]);

                $table = 'emp_qualifications';
                $inputArr = [
                    'employee_id' => $request->employee_id,
                    'qualification' => $request->qualification,
                    'institute' => $request->institute,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Qualification  added successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Qualification', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeeDesignation(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                   'emp_designation_name' => 'required',
                   'designation_status' => 'required',
                ]);

                $table = 'emp_qualifications';
                $idColumn = 'id';
                $inputArr = [
                    'emp_designation_name' => $request->emp_designation_name,
                    'status' => $request->designation_status,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Qualification updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Qualification', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeeDesignation($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Qualifications';
        $table = 'emp_qualifications';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeeDesignations()
    {
        $table = 'emp_qualifications';
        $fields = '*';
        $employee_qualifications = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $employee_qualifications], 200);
    }

    public function getEmployeeDesignationById($id){
        $idColumn = 'id';
        $table = 'emp_qualifications';
        $fields = '*';
        $employee_qualifications = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $employee_qualifications], 200);
    }
}
