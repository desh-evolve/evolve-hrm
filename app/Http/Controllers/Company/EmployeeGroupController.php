<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeeGroupController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee group', ['only' => [
            'index', 
            'getAllEmployeeGroups', 
            'getEmployeeGroupById', 
        ]]);
        $this->middleware('permission:create employee group', ['only' => ['createEmployeeGroup']]);
        $this->middleware('permission:update employee group', ['only' => ['updateEmployeeGroup']]);
        $this->middleware('permission:delete employee group', ['only' => ['deleteEmployeeGroup']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.employee_group.index');
    }

    public function createEmployeeGroup(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'emp_group_name' => 'required',
                    'group_status' => 'required',
                ]);

                $table = 'com_employee_groups';
                $inputArr = [
                    'emp_group_name' => $request->emp_group_name,
                    'status' => $request->group_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Group  added successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Group', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeeGroup(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                   'emp_group_name' => 'required',
                   'group_status' => 'required',
                ]);

                $table = 'com_employee_groups';
                $idColumn = 'id';
                $inputArr = [
                    'emp_group_name' => $request->emp_group_name,
                    'status' => $request->group_status,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Group updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Group', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeeGroup($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Groups';
        $table = 'com_employee_groups';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeeGroups()
    {
        $table = 'com_employee_groups';
        $fields = '*';
        $employee_groups = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $employee_groups], 200);
    }

    public function getEmployeeGroupById($id){
        $idColumn = 'id';
        $table = 'com_employee_groups';
        $fields = '*';
        $employee_groups = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $employee_groups], 200);
    }
}
