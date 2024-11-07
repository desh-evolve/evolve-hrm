<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeeFamilyController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee family', ['only' => [
            'index',
            'getAllEmployeeFamily',
            'getAllEmployeeList',
            'getEmployeeFamilyById',
            'getSingleEmployeeFamily',
        ]]);
        $this->middleware('permission:create employee family', ['only' => ['createEmployeeFamily']]);
        $this->middleware('permission:update employee family', ['only' => ['updateEmployeeFamily']]);
        $this->middleware('permission:delete employee family', ['only' => ['deleteEmployeeFamily']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('employee_family.index');
    }

    public function createEmployeeFamily(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'employee_id' => 'required',
                    'name' => 'required',
                    'relationship' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                ]);

                $table = 'emp_family';
                $inputArr = [
                    'employee_id' => $request->employee_id,
                    'name' => $request->name,
                    'relationship' => $request->relationship,
                    'dob' => $request->dob,
                    'nic' => $request->nic,
                    'gender' => $request->gender,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'notes' => $request->notes,
                    'status' => $request->employee_family_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Family Detail  added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Family Detail', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeeFamily(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'employee_id' => 'required',
                    'name' => 'required',
                    'relationship' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                ]);

                $table = 'emp_family';
                $idColumn = 'id';
                $inputArr = [
                    'employee_id' => $request->employee_id,
                    'name' => $request->name,
                    'relationship' => $request->relationship,
                    'dob' => $request->dob,
                    'nic' => $request->nic,
                    'gender' => $request->gender,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'notes' => $request->notes,
                    'status' => $request->employee_family_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Family Detail updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Family Detail', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeeFamily($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Family Detail';
        $table = 'emp_family';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeeFamily()
    {
        $table = 'emp_family';
        $fields = '*';
        $employee_family = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $employee_family], 200);
    }

    public function getEmployeeFamilyById($id)
    {
        
        $idColumn = 'employee_id';
        $table = 'emp_family';
        $fields = '*';
        $employee_family = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $employee_family], 200);
    }
    public function getEmployeeList()
    {

        $employees = $this->common->commonGetAll('emp_employees', '*');
        // $employees = $this->common->commonGetAll($table, $fields);
        return response()->json([
            // 'data' => [
            //     'employees' => $employees,
            // ]
            'data' => $employees,
        ], 200);
    }

    public function getSingleEmployeeFamily($id)
    {
        $idColumn = 'id';
        $table = 'emp_family';
        $fields = '*';
        $employee_family = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $employee_family], 200);
    }
}
