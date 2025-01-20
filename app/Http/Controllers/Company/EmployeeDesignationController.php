<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeeDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *table = emp_designation_name
     */
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee designation', ['only' => [
            'index',
            'getAllEmployeeDesignations',
            'getEmployeeDesignationById',
        ]]);
        $this->middleware('permission:create employee designation', ['only' => ['createEmployeeDesignation']]);
        $this->middleware('permission:update employee designation', ['only' => ['updateEmployeeDesignation']]);
        $this->middleware('permission:delete employee designation', ['only' => ['deleteEmployeeDesignation']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.employee_designation.index');
    }

    public function createEmployeeDesignation(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'emp_designation_name' => 'required',
                    'designation_status' => 'required',
                ]);

                $table = 'com_user_designations';
                $inputArr = [
                    'emp_designation_name' => $request->emp_designation_name,
                    'status' => $request->designation_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Designation  added successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Designation', 'data' => []], 500);
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

                $table = 'com_user_designations';
                $idColumn = 'id';
                $inputArr = [
                    'emp_designation_name' => $request->emp_designation_name,
                    'status' => $request->designation_status,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Designation updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Designation', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeeDesignation($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Designations';
        $table = 'com_user_designations';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeeDesignations()
    {
        $table = 'com_user_designations';
        $fields = '*';
        $user_designations = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $user_designations], 200);
    }

    public function getEmployeeDesignationById($id){
        $idColumn = 'id';
        $table = 'com_user_designations';
        $fields = '*';
        $user_designations = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_designations], 200);
    }

}
