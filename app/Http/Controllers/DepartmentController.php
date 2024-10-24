<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class DepartmentController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view department', ['only' => [
            'index', 
            'getAllDepartments',
            'getDepartmentByDepartmentId', 
            'getDepartmentsByBranchId', 
            'getDepartmentDropdownData'
        ]]);
        $this->middleware('permission:create department', ['only' => ['createDepartment']]);
        $this->middleware('permission:update department', ['only' => ['updateDepartment']]);
        $this->middleware('permission:delete department', ['only' => ['deleteDepartment']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.department.index');
    }

    //desh(2024-10-22)
    public function getDepartmentDropdownData(){
        $branches = $this->common->commonGetAll('com_branches', '*');
        return response()->json([
            'data' => [
                'branches' => $branches,
            ]
        ], 200);
    }

    //================================================================================================================================
    
    //desh(2024-10-21)
    public function createDepartment(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'department_name' => 'required|string|max:255',
                ]);
    
                $table = 'com_departments';
                $inputArr = [
                    'department_name' => $request->department_name,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
    
                $insertId = $this->common->commonSave($table, $inputArr);
    
                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Department added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add department', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    //desh(2024-10-21)
    public function updateDepartment(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'department_name' => 'required|string|max:255',
                ]);

                $table = 'com_departments';
                $idColumn = 'id';
                $inputArr = [
                    'department_name' => $request->department_name,
                    'updated_by' => Auth::user()->id,
                ];

                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Department updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update department', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    //desh(2024-10-21)
    public function deleteDepartment($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Department';
        $table = 'com_departments';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    //desh(2024-10-21)
    public function getAllDepartments()
    {
        $table = 'com_departments';
        $fields = '*';
        $departments = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $departments], 200);
    }

    //desh(2024-10-21)
    public function getDepartmentByDepartmentId($id)
    {
        $idColumn = 'id';
        $table = 'com_departments';
        $fields = '*';
        $department = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $department], 200);
    }

    //desh(2024-10-21)
    public function getDepartmentsByBranchId($branch_id)
    {
        $id = $branch_id;
        $idColumn = 'branch_id';
        $table = 'com_branch_departments';
        $fields = ['com_departments.id as department_id', 'branch_id', 'department_name'];
        $joinsArr = [
            'com_departments' => ['com_departments.id', '=', 'com_branch_departments.department_id']
        ];
        $whereArr = [
            'com_departments.status' => 'active',
        ];
        $departments = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinsArr, $whereArr);
        return response()->json(['data' => $departments], 200);
    }
            

}