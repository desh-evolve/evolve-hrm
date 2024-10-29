<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeeController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee', ['only' => [
            'employee_list',
            'employee_form',
            'getAllEmployees',
            'getEmployeeByEmployeeId',
        ]]);
        $this->middleware('permission:create employee', ['only' => ['createEmployee']]);
        $this->middleware('permission:update employee', ['only' => ['updateEmployee']]);
        $this->middleware('permission:delete employee', ['only' => ['deleteEmployee']]);

        $this->common = new CommonModel();
    }

    public function employee_list()
    {
        return view('employee.emp_list');
    }

    public function employee_form()
    {
        return view('employee.emp_form');
    }

    public function createEmployee(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'user_id' => 'required|integer',
                    'title' => 'required|string|max:20',
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'full_name' => 'nullable|string|max:255',
                    'name_with_initials' => 'nullable|string|max:255',
                    'address_1' => 'required|string|max:255',
                    'address_2' => 'nullable|string|max:255',
                    'address_3' => 'nullable|string|max:255',
                    'nic' => 'required|string|max:255|unique:emp_employees,nic',
                    'country_id' => 'required|integer',
                    'province_id' => 'required|integer',
                    'city_id' => 'required|integer',
                    'postal_code' => 'nullable|string|max:255',
                    'contact_1' => 'required|string|max:20',
                    'contact_2' => 'nullable|string|max:20',
                    'work_contact' => 'nullable|string|max:20',
                    'home_contact' => 'nullable|string|max:20',
                    'immediate_contact_person' => 'nullable|string|max:255',
                    'immediate_contact_no' => 'nullable|string|max:20',
                    'personal_email' => 'nullable|email|max:255',
                    'work_email' => 'nullable|email|max:255',
                    'epf_reg_no' => 'nullable|string|max:255',
                    'religion' => 'required|integer',
                    'dob' => 'nullable|date',
                    'gender' => 'nullable|string|max:10',
                    'bond_period' => 'nullable|string|max:255',
                    'employee_status' => 'required|integer',
                    'marital_status' => 'nullable|string|max:20',
                    'employee_image' => 'nullable|string|max:255',
                    'punch_machine_user_id' => 'nullable|integer',
                    'designation_id' => 'required|integer',
                    'employee_group_id' => 'required|integer',
                    'policy_group_id' => 'required|integer',
                    'appointment_date' => 'required|date',
                    'appointment_note' => 'nullable|string',
                    'terminated_date' => 'nullable|date',
                    'terminated_note' => 'nullable|string',
                    'employment_type_id' => 'required|integer',
                    'employment_time' => 'nullable|integer',
                    'confirmed_date' => 'nullable|date',
                    'resigned_date' => 'nullable|date',
                    'retirement_date' => 'nullable|date',
                    'currency_id' => 'nullable|integer',
                    'pay_period_id' => 'nullable|integer',
                    'role_id' => 'nullable|integer',
                    'email' => 'required|email|max:255|unique:users,email', // Validate email in users table
                ]);

                // Check if employee with the given work_email already exists
                if (
                    ($request->work_email && DB::table('emp_employees')->where('work_email', $request->work_email)->exists()) ||
                    ($request->personal_email && DB::table('emp_employees')->where('personal_email', $request->personal_email)->exists())
                ) {
                    return response()->json(['status' => 'error', 'message' => 'Employee with this email already exists'], 409);
                }

                $table = 'users';

                $userArr = [
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password ?? 'password'),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $userId = $this->common->commonSave($table, $userArr);

                if ($userId) {

                    $table = 'emp_employees';

                    $inputArr = [
                        'user_id' => $userId,
                        'title' => $request->title,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'full_name' => $request->full_name,
                        'name_with_initials' => $request->name_with_initials,
                        'address_1' => $request->address_1,
                        'address_2' => $request->address_2,
                        'address_3' => $request->address_3,
                        'nic' => $request->nic,
                        'country_id' => $request->country_id,
                        'province_id' => $request->province_id,
                        'city_id' => $request->city_id,
                        'postal_code' => $request->postal_code,
                        'contact_1' => $request->contact_1,
                        'contact_2' => $request->contact_2,
                        'work_contact' => $request->work_contact,
                        'home_contact' => $request->home_contact,
                        'immediate_contact_person' => $request->immediate_contact_person,
                        'immediate_contact_no' => $request->immediate_contact_no,
                        'personal_email' => $request->personal_email,
                        'work_email' => $request->work_email,
                        'epf_reg_no' => $request->epf_reg_no,
                        'religion' => $request->religion,
                        'dob' => $request->dob,
                        'gender' => $request->gender,
                        'bond_period' => $request->bond_period,
                        'employee_status' => $request->employee_status,
                        'marital_status' => $request->marital_status,
                        'employee_image' => $request->employee_image,
                        'punch_machine_user_id' => $request->punch_machine_user_id,
                        'designation_id' => $request->designation_id,
                        'employee_group_id' => $request->employee_group_id,
                        'policy_group_id' => $request->policy_group_id,
                        'appointment_date' => $request->appointment_date,
                        'appointment_note' => $request->appointment_note,
                        'terminated_date' => $request->terminated_date,
                        'terminated_note' => $request->terminated_note,
                        'employment_type_id' => $request->employment_type_id,
                        'employment_time' => $request->employment_time,
                        'confirmed_date' => $request->confirmed_date,
                        'resigned_date' => $request->resigned_date,
                        'retirement_date' => $request->retirement_date,
                        'currency_id' => $request->currency_id,
                        'pay_period_id' => $request->pay_period_id,
                        'role_id' => $request->role_id,

                        'status' => $request->employee_status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];

                    $insertId = $this->common->commonSave($table, $inputArr);

                    return response()->json(['status' => 'success', 'message' => 'Employee added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add Employee'], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function updateEmployeeDesignation(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required|integer',
                    'title' => 'required|string|max:20',
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'full_name' => 'nullable|string|max:255',
                    'name_with_initials' => 'nullable|string|max:255',
                    'address_1' => 'required|string|max:255',
                    'address_2' => 'nullable|string|max:255',
                    'address_3' => 'nullable|string|max:255',
                    'nic' => 'required|string|max:255|unique:emp_employees,nic',
                    'country_id' => 'required|integer',
                    'province_id' => 'required|integer',
                    'city_id' => 'required|integer',
                    'postal_code' => 'nullable|string|max:255',
                    'contact_1' => 'required|string|max:20',
                    'contact_2' => 'nullable|string|max:20',
                    'work_contact' => 'nullable|string|max:20',
                    'home_contact' => 'nullable|string|max:20',
                    'immediate_contact_person' => 'nullable|string|max:255',
                    'immediate_contact_no' => 'nullable|string|max:20',
                    'personal_email' => 'nullable|email|max:255',
                    'work_email' => 'nullable|email|max:255',
                    'epf_reg_no' => 'nullable|string|max:255',
                    'religion' => 'required|integer',
                    'dob' => 'nullable|date',
                    'gender' => 'nullable|string|max:10',
                    'bond_period' => 'nullable|string|max:255',
                    'employee_status' => 'required|integer',
                    'marital_status' => 'nullable|string|max:20',
                    'employee_image' => 'nullable|string|max:255',
                    'punch_machine_user_id' => 'nullable|integer',
                    'designation_id' => 'required|integer',
                    'employee_group_id' => 'required|integer',
                    'policy_group_id' => 'required|integer',
                    'appointment_date' => 'required|date',
                    'appointment_note' => 'nullable|string',
                    'terminated_date' => 'nullable|date',
                    'terminated_note' => 'nullable|string',
                    'employment_type_id' => 'required|integer',
                    'employment_time' => 'nullable|integer',
                    'confirmed_date' => 'nullable|date',
                    'resigned_date' => 'nullable|date',
                    'retirement_date' => 'nullable|date',
                    'currency_id' => 'nullable|integer',
                    'pay_period_id' => 'nullable|integer',
                    'role_id' => 'nullable|integer',
                    // 'email' => 'required|email|max:255|unique:users,email', // Validate email in users table
                ]);

                $table = 'emp_employees';
                $idColumn = 'id';
                $inputArr = [
                    'title' => $request->title,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'full_name' => $request->full_name,
                    'name_with_initials' => $request->name_with_initials,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'address_3' => $request->address_3,
                    'nic' => $request->nic,
                    'country_id' => $request->country_id,
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                    'postal_code' => $request->postal_code,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'work_contact' => $request->work_contact,
                    'home_contact' => $request->home_contact,
                    'immediate_contact_person' => $request->immediate_contact_person,
                    'immediate_contact_no' => $request->immediate_contact_no,
                    'personal_email' => $request->personal_email,
                    'work_email' => $request->work_email,
                    'epf_reg_no' => $request->epf_reg_no,
                    'religion' => $request->religion,
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'bond_period' => $request->bond_period,
                    'employee_status' => $request->employee_status,
                    'marital_status' => $request->marital_status,
                    'employee_image' => $request->employee_image,
                    'punch_machine_user_id' => $request->punch_machine_user_id,
                    'designation_id' => $request->designation_id,
                    'employee_group_id' => $request->employee_group_id,
                    'policy_group_id' => $request->policy_group_id,
                    'appointment_date' => $request->appointment_date,
                    'appointment_note' => $request->appointment_note,
                    'terminated_date' => $request->terminated_date,
                    'terminated_note' => $request->terminated_note,
                    'employment_type_id' => $request->employment_type_id,
                    'employment_time' => $request->employment_time,
                    'confirmed_date' => $request->confirmed_date,
                    'resigned_date' => $request->resigned_date,
                    'retirement_date' => $request->retirement_date,
                    'currency_id' => $request->currency_id,
                    'pay_period_id' => $request->pay_period_id,
                    'role_id' => $request->role_id,

                    'status' => $request->employee_status,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Employee updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Employee', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployee($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employees';
        $table = 'emp_employees';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }
    public function getAllEmployees()
    {
        $table = 'emp_employees';
        $fields = '*';
        $employee_designations = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $employee_designations], 200);
    }
    public function getEmployeeByEmployeeId($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $employees = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $employees], 200);
    }
}
