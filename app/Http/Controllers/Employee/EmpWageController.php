<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmpWageController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee wage', ['only' => [
            'index',
            'getAllEmployeeWage',
            'getAllEmployeeList',
            'getEmployeeWageById',
            'getSingleEmployeeWage',
        ]]);
        $this->middleware('permission:create employee wage', ['only' => ['createEmployeeWage']]);
        $this->middleware('permission:update employee wage', ['only' => ['updateEmployeeWage']]);
        $this->middleware('permission:delete employee wage', ['only' => ['deleteEmployeeWage']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('employee_wage.index');
    }

    public function createEmployeeWage(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'employee_id' => 'required',
                    'wage_group_id' => 'required',
                    'wage_type_id' => 'required',
                    'wage' => 'required',
                    'effective_date' => 'required',
                    'hourly_rate' => 'required',
                ]);

                $table = 'emp_wage';
                $inputArr = [
                    'employee_id' => $request->employee_id,
                    'wage_group_id' => $request->wage_group_id,
                    'wage_type_id' => $request->wage_type_id,
                    'wage' => $request->wage,
                    'budgetary_allowance' => 0,
                    'effective_date' => $request->effective_date,
                    'weekly_time' => $request->weekly_time,
                    'hourly_rate' => $request->hourly_rate,
                    'note' => $request->note,
                    'status' => $request->employee_wage_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Wage Detail  added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Wage Detail', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeeWage(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'employee_id' => 'required',
                    'wage_group_id' => 'required',
                    'wage_type_id' => 'required',
                    'wage' => 'required',
                    'effective_date' => 'required',
                    'hourly_rate' => 'required',
                ]);

                $table = 'emp_wage';
                $idColumn = 'id';
                $inputArr = [
                    'employee_id' => $request->employee_id,
                    'wage_group_id' => $request->wage_group_id,
                    'wage_type_id' => $request->wage_type_id,
                    'wage' => $request->wage,
                    'budgetary_allowance' => 0,
                    'effective_date' => $request->effective_date,
                    'weekly_time' => $request->weekly_time,
                    'hourly_rate' => $request->hourly_rate,
                    'note' => $request->note,
                    'status' => $request->employee_wage_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Wage Detail updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Wage Detail', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeeWage($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Wage Detail';
        $table = 'emp_wage';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeeWage()
    {
        $table = 'emp_wage';
        $fields = '*';
        $employee_wage = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $employee_wage], 200);
    }

    public function getEmployeeWageById($id)
    {

        $idColumn = 'employee_id';
        $table = 'emp_wage';
        $fields = ['emp_wage.*', 'emp_wage.id as id', 'com_wage_groups.wage_group_name' , 'com_wage_type.name as wage_type_name', 'com_wage_type.wage_type'];
        $joinsArr = [
            'com_wage_groups' => ['com_wage_groups.id', '=', 'emp_wage.wage_group_id'],
            'com_wage_type' => ['com_wage_type.id', '=', 'emp_wage.wage_type_id']
        ];

        $whereArr = ['com_wage_groups.status' => 'active', 'com_wage_type.status' => 'active'];

        $employee_wage = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinsArr, $whereArr);
        return response()->json(['data' => $employee_wage], 200);
    }
    public function getDropDownList()
    {

        $employees = $this->common->commonGetAll('emp_employees', '*');
        $wageGroups = $this->common->commonGetAll('com_wage_groups', '*');
        $wageTypes = $this->common->commonGetAll('com_wage_type', '*');
        // $employees = $this->common->commonGetAll($table, $fields);
        return response()->json([
            'data' => [
                'employees' => $employees,
                'wageGroups' => $wageGroups,
                'wageTypes' => $wageTypes,
            ]
            // 'data' => $employees,
        ], 200);
    }

    public function getSingleEmployeeWage($id)
    {
        $idColumn = 'id';
        $table = 'emp_wage';
        $fields = '*';
        $employee_wage = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $employee_wage], 200);
    }
}
