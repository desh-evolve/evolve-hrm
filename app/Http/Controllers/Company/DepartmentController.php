<?php

namespace App\Http\Controllers\Company;

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
            'employees', 
            'getAllDepartments',
            'getDepartmentByDepartmentId', 
            'getDepartmentDropdownData',
            'getDepartmentBranchEmployees',
            'getDepartmentEmployeesDropdownData'
        ]]);
        $this->middleware('permission:create department', ['only' => ['createDepartment', 'createDepartmentEmployees']]);
        $this->middleware('permission:update department', ['only' => ['updateDepartment']]);
        $this->middleware('permission:delete department', ['only' => ['deleteDepartment', 'deleteDepartmentBranchEmployees']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.department.index');
    }

    public function employees()
    {
        return view('company.department.employees');
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
                    'department_status' => 'required|string',
                    'branches' => 'required|string',
                ]);
    
                $table = 'com_departments';
                $inputArr = [
                    'department_name' => $request->department_name,
                    'status' => $request->department_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
    
                $insertId = $this->common->commonSave($table, $inputArr);

                // Handle branches
                if ($request->has('branches')) {
                    $table2 = 'com_branch_departments';
                    $branches = explode(',', $request->branches);

                    foreach ($branches as $branch) {
                        $inputArr2 = [
                            'department_id' => $insertId,
                            'branch_id' => trim($branch),
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ];
                        $this->common->commonSave($table2, $inputArr2);
                    }
                }
    
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
                // Validate the request
                $request->validate([
                    'department_name' => 'required|string|max:255',
                    'department_status' => 'required|string',
                    'branches' => 'required|string',  // Assuming branches are a comma-separated string
                ]);
    
                $table = 'com_departments';
                $idColumn = 'id';
                $inputArr = [
                    'department_name' => $request->department_name,
                    'status' => $request->department_status,
                    'updated_by' => Auth::user()->id,
                ];
    
                // Update department information
                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);
    
                if (!$updatedId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update department', 'data' => []], 500);
                }
    
                // Handle branches - Update department branches
                if ($request->has('branches')) {
                    $table2 = 'com_branch_departments';
                    $employeeTable = 'com_branch_department_employees';

                    $newBranches = explode(',', $request->branches);  // Split new branches string into an array
                    $newBranches = array_map('trim', $newBranches);   // Trim any spaces

                    // Get the current branches for this department from the database
                    $currentBranches = DB::table($table2)
                        ->where('department_id', $id)
                        ->where('status', 'active')
                        ->pluck('branch_id')
                        ->toArray();  // Fetch the current branches as an array

                    // Find branches to delete (present in DB but not in the new set)
                    $branchesToDelete = array_diff($currentBranches, $newBranches);
                    if (!empty($branchesToDelete)) {
                        // Mark branches as deleted
                        DB::table($table2)
                            ->where('department_id', $id)
                            ->whereIn('branch_id', $branchesToDelete)
                            ->update(['status' => 'delete']);  // Assuming you want to mark them as deleted

                        // Also mark related employees as deleted based on department_id and branch_id
                        DB::table($employeeTable)
                            ->where('department_id', $id)
                            ->whereIn('branch_id', $branchesToDelete)
                            ->update(['status' => 'delete']);
                    }

                    // Find branches to add (present in the new set but not in the DB)
                    $branchesToAdd = array_diff($newBranches, $currentBranches);
                    if (!empty($branchesToAdd)) {
                        foreach ($branchesToAdd as $branch) {
                            $inputArr2 = [
                                'department_id' => $id,
                                'branch_id' => $branch,  // New branch to be added
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ];
                            $this->common->commonSave($table2, $inputArr2);
                        }
                    }
                }
    
                return response()->json(['status' => 'success', 'message' => 'Department updated successfully', 'data' => ['id' => $updatedId]], 200);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch and handle database query exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred due to: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }    

    //desh(2024-10-21)
    public function deleteDepartment($id)
    {
        $res =  $this->common->commonDelete($id, ['id' => $id], 'Department', 'com_departments');
        $this->common->commonDelete($id, ['department_id' => $id], 'Department Branches', 'com_branch_departments');
        $this->common->commonDelete($id, ['department_id' => $id], 'Department Branches Employees', 'com_branch_department_employees');

        return $res;
    }

    //desh(2024-10-21)
    public function getAllDepartments()
    {
        $table = 'com_departments';
        $fields = '*';
        $departments = $this->common->commonGetAll($table, $fields, [], [], true);
        return response()->json(['data' => $departments], 200);
    }

    //desh(2024-10-21)
    public function getDepartmentByDepartmentId($id)
    {
        $idColumn = 'id';
        $table = 'com_departments';
        $fields = ['com_departments.*'];  // You can customize the fields as needed
    
        // Define connections to other tables
        $connections = [
            'com_branch_departments' => [
                'con_fields' => ['branch_id', 'department_id', 'branch_name'],  // Fields to select from connected table
                'con_where' => ['com_branch_departments.department_id' => 'id'],  // Link to the main table (department_id)
                'con_joins' => [
                    'com_branches' => ['com_branches.id', '=', 'com_branch_departments.branch_id'],
                ],
                'con_name' => 'branch_departments',  // Alias to store connected data in the result
                'except_deleted' => true,  // Filter out soft-deleted records
            ],
        ];
    
        // Fetch the department with connections
        $department = $this->common->commonGetById($id, $idColumn, $table, $fields, [], [], true, $connections);
    
        return response()->json(['data' => $department], 200);
    }    
            
    //desh(2024-10-25)
    public function getDepartmentBranchEmployees($branch_id, $department_id)
    {
        $table = 'com_branch_department_employees';
        $fields = ['com_branch_department_employees.employee_id', 'first_name', 'last_name'];
        $joinArr = [
            'emp_employees' => ['emp_employees.id', '=', 'com_branch_department_employees.employee_id']
        ];
        $whereArr = [
            'branch_id' => $branch_id,
            'department_id' => $department_id
        ];
        // Fetch the department with connections
        $employees = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr);

        return response()->json(['data' => $employees], 200);
    }

    //desh(2024-10-25)
    public function deleteDepartmentBranchEmployees($department_id, $branch_id, $employee_id){
        $whereArr = [
            'department_id' => $department_id,
            'branch_id' => $branch_id,
            'employee_id' => $employee_id,
        ];
        $res =  $this->common->commonDelete($employee_id, $whereArr, 'Department Employee', 'com_branch_department_employees');
        return $res;
    }

    //desh(2024-10-25)
    public function getDepartmentEmployeesDropdownData(){
        $emp = $this->common->commonGetAll('emp_employees', '*');
        return response()->json([
            'data' => [
                'employees' => $emp,
            ]
        ], 200);
    }

    //desh(2024-10-25)

    //first you should check the db if same user exists by branch_id, department_id and employee_id and status = 'active'. if exist don't add. if not add. if status = 'delete' make id 'active'. if new array doesn't contain the employee_id make status = 'delete'
    public function createDepartmentEmployees(Request $request) {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'branch_id' => 'required|integer|max:255',
                    'department_id' => 'required|integer',
                    'employees' => 'required|string',
                ]);
    
                $table = 'com_branch_department_employees';
                $successCount = 0;
                
                // Get employees array from the request
                $employeeIds = explode(',', $request->employees);
                $employeeIds = array_map('trim', $employeeIds);
    
                // Fetch current employees from DB for given branch and department
                $existingEmployees = DB::table($table)
                    ->where('branch_id', $request->branch_id)
                    ->where('department_id', $request->department_id)
                    ->pluck('status', 'employee_id')
                    ->toArray();
    
                // Loop through each employee ID in the request
                foreach ($employeeIds as $employeeId) {
                    if (isset($existingEmployees[$employeeId])) {
                        if ($existingEmployees[$employeeId] === 'active') {
                            // Employee already active, no need to add
                            continue;
                        } elseif ($existingEmployees[$employeeId] === 'delete') {
                            // Reactivate deleted employee
                            DB::table($table)
                                ->where('branch_id', $request->branch_id)
                                ->where('department_id', $request->department_id)
                                ->where('employee_id', $employeeId)
                                ->update([
                                    'status' => 'active',
                                    'updated_by' => Auth::user()->id,
                                    'updated_at' => now(),
                                ]);
                            $successCount++;
                        }
                    } else {
                        // New employee - insert into database
                        $inputArr = [
                            'department_id' => $request->department_id,
                            'branch_id' => $request->branch_id,
                            'employee_id' => $employeeId,
                            'status' => 'active',
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        DB::table($table)->insert($inputArr);
                        $successCount++;
                    }
                }
    
                // Mark employees not in the new array as deleted
                $employeesToDelete = array_diff(array_keys($existingEmployees), $employeeIds);
                if (!empty($employeesToDelete)) {
                    DB::table($table)
                        ->where('branch_id', $request->branch_id)
                        ->where('department_id', $request->department_id)
                        ->whereIn('employee_id', $employeesToDelete)
                        ->update([
                            'status' => 'delete',
                            'updated_by' => Auth::user()->id,
                            'updated_at' => now(),
                        ]);
                }
    
                // Return the response based on success count
                if ($successCount > 0) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Department employees updated successfully',
                        'data' => ['updated_count' => $successCount]
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No changes were made to department employees',
                        'data' => []
                    ], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred due to ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    

}