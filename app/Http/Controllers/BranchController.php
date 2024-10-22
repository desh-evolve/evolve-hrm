<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class BranchController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view branch', ['only' => [
            'branch', 
            'department', 
            'getAllBranches', 
            'getBranchByBranchId', 
            'getAllDepartments',
            'getDepartmentByDepartmentId', 
            'getDepartmentsByBranchId', 
            'getAllDivisions',
            'getDivisionByDivisionId', 
            'getDivisionsByDepartmentId',
            'getAllDropdownData'
        ]]);
        $this->middleware('permission:create branch', ['only' => ['createBranch', 'createDepartment', 'createDivision']]);
        $this->middleware('permission:update branch', ['only' => ['updateBranch', 'updateDepartment', 'updateDivision']]);
        $this->middleware('permission:delete branch', ['only' => ['deleteBranch', 'deleteDepartment', 'deleteDivision']]);

        $this->common = new CommonModel();
    }

    public function branch()
    {
        return view('company.branch.branch');
    }

    public function department()
    {
        return view('company.branch.department');
    }

    //desh(2024-10-22)
    public function getAllDropdownData(){
        $countries = $this->common->commonGetAll('loc_countries', '*');
        $provinces = $this->common->commonGetAll('loc_provinces', '*');
        $cities = $this->common->commonGetAll('loc_cities', '*');
        return response()->json([
            'data' => [
                'countries' => $countries,
                'provinces' => $provinces,
                'cities' => $cities,
            ]
        ], 200);
    }

    //================================================================================================================================
    // branch
    //================================================================================================================================

    //desh(2024-10-21)
    public function createBranch(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'company_id' => 'required|integer',
                    'branch_name' => 'required|string|max:255',
                    'short_name' => 'nullable|string|max:100',
                    'address_1' => 'required|string|max:255',
                    'city_id' => 'required|integer',
                    'province_id' => 'required|integer',
                    'country_id' => 'required|integer',
                    'contact_1' => 'required|string|max:15',
                    'email' => 'required|email|unique:com_branches,email',
                ]);
    
                $table = 'com_branches';
                $inputArr = [
                    'company_id' => $request->company_id,
                    'branch_name' => $request->branch_name,
                    'short_name' => $request->short_name,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city_id' => $request->city_id,
                    'province_id' => $request->province_id,
                    'country_id' => $request->country_id,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'email' => $request->email,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
    
                $insertId = $this->common->commonSave($table, $inputArr);
    
                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Branch added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add branch', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    //desh(2024-10-21)
    public function updateBranch(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'company_id' => 'required|integer',
                    'branch_name' => 'required|string|max:255',
                    'short_name' => 'nullable|string|max:100',
                    'address_1' => 'required|string|max:255',
                    'city_id' => 'required|integer',
                    'province_id' => 'required|integer',
                    'country_id' => 'required|integer',
                    'contact_1' => 'required|string|max:15',
                    'email' => 'required|email|unique:com_branches,email,' . $id,
                ]);
    
                $table = 'com_branches';
                $idColumn = 'id';
                $inputArr = [
                    'company_id' => $request->company_id,
                    'branch_name' => $request->branch_name,
                    'short_name' => $request->short_name,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city_id' => $request->city_id,
                    'province_id' => $request->province_id,
                    'country_id' => $request->country_id,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'email' => $request->email,
                    'updated_by' => Auth::user()->id,
                ];
    
                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);
    
                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Branch updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update branch', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    //desh(2024-10-21)
    public function deleteBranch($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Branch';
        $table = 'com_branches';
    
        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }
    
    //desh(2024-10-21)
    public function getAllBranches()
    {
        $table = 'com_branches';
        $fields = ['com_branches.*', 'com_branches.id as id', 'loc_countries.country_name', 'loc_provinces.province_name', 'loc_cities.city_name'];
        $joinsArr = [
            'loc_countries' => ['loc_countries.id', '=', 'com_branches.country_id'],
            'loc_provinces' => ['loc_provinces.id', '=', 'com_branches.province_id'],
            'loc_cities' => ['loc_cities.id', '=', 'com_branches.city_id']
        ];
        $branches = $this->common->commonGetAll($table, $fields, $joinsArr);
        return response()->json(['data' => $branches], 200);
    }
    
    //desh(2024-10-21)
    public function getBranchByBranchId($id)
    {
        $idColumn = 'id';
        $table = 'com_branches';
        $fields = '*';
        $branch = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $branch], 200);
    }    

    //================================================================================================================================
    // department
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

    //================================================================================================================================
    // division
    //================================================================================================================================
    
    //desh(2024-10-21)
    public function createDivision(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'division_name' => 'required|string|max:255',
                    'department_id' => 'required|integer',
                ]);
    
                $table = 'com_divisions';
                $inputArr = [
                    'division_name' => $request->division_name,
                    'department_id' => $request->department_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
    
                $insertId = $this->common->commonSave($table, $inputArr);
    
                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Division added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add division', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    //desh(2024-10-21)
    public function updateDivision(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'division_name' => 'required|string|max:255',
                    'department_id' => 'required|integer',
                ]);

                $table = 'com_divisions';
                $idColumn = 'id';
                $inputArr = [
                    'division_name' => $request->division_name,
                    'department_id' => $request->department_id,
                    'updated_by' => Auth::user()->id,
                ];

                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Division updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update division', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    //desh(2024-10-21)
    public function deleteDivision($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Division';
        $table = 'com_divisions';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    //desh(2024-10-21)
    public function getAllDivisions()
    {
        $table = 'com_divisions';
        $fields = '*';
        $divisions = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $divisions], 200);
    }

    //desh(2024-10-21)
    public function getDivisionByDivisionId($id)
    {
        $idColumn = 'id';
        $table = 'com_divisions';
        $fields = '*';
        $division = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $division], 200);
    }

    //desh(2024-10-21)
    public function getDivisionsByDepartmentId($department_id)
    {
        $id = $department_id;
        $idColumn = 'department_id';
        $table = 'com_divisions';
        $fields = '*';
        $departments = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $departments], 200);
    }
            

}