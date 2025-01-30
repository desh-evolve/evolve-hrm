<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class StationController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view station', ['only' => [
            'index',
            'getDropdownList',
            'getAllStation',
            'getStationById',
        ]]);
        $this->middleware('permission:create station', ['only' => ['createStation']]);
        $this->middleware('permission:update station', ['only' => ['updateStation']]);
        $this->middleware('permission:delete station', ['only' => ['deleteStation']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.station.index');
    }

    public function form()
    {
        return view('company.station.form');
    }

    public function getDropdownList()
    {
        $branches = $this->common->commonGetAll('com_branches', '*');

        // Define connections to other tables
        $connections = [
            'com_branch_departments' => [
                'con_fields' => ['branch_id', 'department_id', 'branch_name'],  // Fields to select from connected table
                'con_where' => ['com_branch_departments.department_id' => 'id'],  // Link to the main table (department_id)
                'con_joins' => [
                    'com_branches' => ['com_branches.id', '=', 'com_branch_departments.branch_id'],
                ],
                'con_name' => 'branch_departments',  // Alias to store connected data in the result
                'except_deleted' => false,  // Filter out soft-deleted records
            ],
        ];

        // Fetch the department with connections
        $departments = $this->common->commonGetAll('com_departments', ['com_departments.*'], [], [], false, $connections);
        $user_groups = $this->common->commonGetAll('com_employee_groups', '*');
        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        return response()->json([
            'data' => [
                'branches' => $branches,
                'departments' => $departments,
                'user_groups' => $user_groups,
                'users' => $users,
            ]
        ], 200);
    }

    public function createStation(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'emp_designation_name' => 'required',
                    'designation_status' => 'required',
                ]);

                $table = 'com_stations';
                $inputArr = [
                    'emp_designation_name' => $request->emp_designation_name,
                    'status' => $request->designation_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Designation  added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Designation', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateStation(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'emp_designation_name' => 'required',
                    'designation_status' => 'required',
                ]);

                $table = 'com_stations';
                $idColumn = 'id';
                $inputArr = [
                    'emp_designation_name' => $request->emp_designation_name,
                    'status' => $request->designation_status,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Designation updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Designation', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteStation($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Designations';
        $table = 'com_stations';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllStation()
    {
        $table = 'com_stations';
        $fields = '*';
        $emp_designations = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $emp_designations], 200);
    }

    public function getStationById($id)
    {
        $idColumn = 'id';
        $table = 'com_stations';
        $fields = '*';
        $emp_designations = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $emp_designations], 200);
    }
}
