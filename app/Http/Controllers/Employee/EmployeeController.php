<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
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
        $this->middleware('permission:view user profile', ['only' => ['user_profile', 'getMyDataByEmployeeId', 'getEmployeeByUserId']]);
        $this->middleware('permission:view user', ['only' => [
            'user_list',
            'getAllEmployees',
            'getEmployeeByEmployeeId',
        ]]);
        $this->middleware('permission:create user', ['only' => ['user_form', 'getEmployeeDropdownData', 'createEmployee']]);
        $this->middleware('permission:update user', ['only' => ['user_form', 'getEmployeeDropdownData', 'updateEmployee']]);
        $this->middleware('permission:delete user', ['only' => ['deleteEmployee']]);

        $this->common = new CommonModel();
    }

    public function user_list()
    {
        return view('user.emp_list');
    }

    public function user_form()
    {
        return view('user.emp_form');
    }

    public function user_profile()
    {
        return view('user.emp_profile');
    }

    public function getEmployeeDropdownData(){
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
        $employee_groups = $this->common->commonGetAll('com_employee_groups', '*');
        $employee_designations = $this->common->commonGetAll('com_employee_designations', '*');
        //policy groups => create table
        $policy_groups = [
            [ 'id' => 1, 'name' => 'PG 1'],
            [ 'id' => 2, 'name' => 'PG 2'],
            [ 'id' => 3, 'name' => 'PG 3'],
        ];
        //user status => create table
        $user_status = [
            [ 'id' => 1, 'name' => 'Active', 'description' => ''],
            [ 'id' => 2, 'name' => 'Leave', 'description' => 'Illness/Injury'],
            [ 'id' => 3, 'name' => 'Leave', 'desription' => 'Maternity/Parental'],
            [ 'id' => 3, 'name' => 'Leave', 'description' => 'Other'],
            [ 'id' => 3, 'name' => 'Terminated', 'description' => ''],
        ];
        $currencies = $this->common->commonGetAll('com_currencies', '*');
        //pay period => create table
        $pay_period = [
            [ 'id' => 1, 'name' => 'Daily'],
            [ 'id' => 2, 'name' => 'Weekly'],
            [ 'id' => 3, 'name' => 'Bi-weekly'],
            [ 'id' => 4, 'name' => 'Monthly'],
            [ 'id' => 5, 'name' => 'Quarterly'],
            [ 'id' => 6, 'name' => 'Yearly'],
        ];

        // add status column to roles table
        //$roles = $this->common->commonGetAll('roles', '*');
        $roles = [
            [ 'id' => 1, 'name' => 'Super Admin', 'value' => 'super-admin'],
            [ 'id' => 2, 'name' => 'Admin', 'value' => 'admin'],
            [ 'id' => 3, 'name' => 'Staff', 'value' => 'staff'],
            [ 'id' => 4, 'name' => 'User', 'value' => 'user'],
        ];
        //religion => create table
        $religion = [
            [ 'id' => 1, 'name' => 'Buddhism'],
            [ 'id' => 2, 'name' => 'Christian'],
            [ 'id' => 3, 'name' => 'Islam'],
            [ 'id' => 4, 'name' => 'Hindu'],
            [ 'id' => 5, 'name' => 'Other'],
        ];

        //employment types => create table
        $employment_types = [
            [ 'id' => 1, 'name' => 'Contract', 'is_duration' => 1],
            [ 'id' => 2, 'name' => 'Training', 'is_duration' => 1],
            [ 'id' => 3, 'name' => 'Permanent (With Probation)', 'is_duration' => 1],
            [ 'id' => 4, 'name' => 'Permanent (Confirmed)', 'is_duration' => 0],
            [ 'id' => 5, 'name' => 'Resign', 'is_duration' => 0],
            [ 'id' => 6, 'name' => 'External', 'is_duration' => 0],
        ];
        
        $countries = $this->common->commonGetAll('loc_countries', '*');
        $provinces = $this->common->commonGetAll('loc_provinces', '*');
        $cities = $this->common->commonGetAll('loc_cities', '*');

        $doc_types = [
            [ 'id' => 1, 'name' => 'GS Certificate' ],
            [ 'id' => 2, 'name' => 'Doc 2' ],
            [ 'id' => 3, 'name' => 'Doc 3' ],
            [ 'id' => 4, 'name' => 'Doc 4' ],
        ];

        return response()->json([
            'data' => [
                'branches' => $branches,
                'departments' => $departments,
                'employee_groups' => $employee_groups,
                'employee_designations' => $employee_designations,
                'policy_groups' => $policy_groups,
                'user_status' => $user_status,
                'currencies' => $currencies,
                'pay_period' => $pay_period,
                'roles' => $roles,
                'religion' => $religion,
                'employment_types' => $employment_types,
                'countries' => $countries,
                'provinces' => $provinces,
                'cities' => $cities,
                'doc_types' => $doc_types,
            ]
        ], 200);
    }

    public function getNextEmployeeId()
    {
        // Using raw SQL to get the next auto-increment ID
        $nextId = DB::select("SHOW TABLE STATUS LIKE 'emp_employees'")[0]->Auto_increment;

        // Return the next ID
        return response()->json([
            'data' => $nextId
        ], 200);
    }

    public function createEmployee(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Validate input fields
            $request->validate([
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
                'postal_code' => 'nullable|string|max:25',
                'contact_1' => 'required|string|max:20',
                'contact_2' => 'nullable|string|max:20',
                'work_contact' => 'nullable|string|max:20',
                'home_contact' => 'nullable|string|max:20',
                'immediate_contact_person' => 'nullable|string|max:255',
                'immediate_contact_no' => 'nullable|string|max:20',
                'personal_email' => 'nullable|email|max:255|unique:emp_employees,personal_email',
                'work_email' => 'nullable|email|max:255|unique:emp_employees,work_email',
                'epf_reg_no' => 'nullable|string|max:255',
                'religion_id' => 'nullable|integer',
                'dob' => 'nullable|date',
                'gender' => 'nullable|string|max:10',
                'bond_period' => 'nullable|string|max:255',
                'user_status' => 'required|integer',
                'marital_status' => 'nullable|string|max:20',
                'user_image' => 'nullable|string|max:255',
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
                'permission_group_id' => 'nullable|string',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:4|max:20', // Password validation
            ]);
    
            //===========================================================================================================
            // create user and user persmission
            //===========================================================================================================
            // Prepare user data and insert into the 'users' table
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // Attach roles to the user
            $user->syncRoles([$request->permission_group_id]);
            //===========================================================================================================
            
            // Prepare user data and insert into the 'emp_employees' table
            $userData = [
                'user_id' => $user->id, // Use the newly created user ID
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
                'religion' => $request->religion_id,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'bond_period' => $request->bond_period,
                'user_status' => $request->user_status,
                'marital_status' => $request->marital_status,
                'user_image' => $request->user_image,
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
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
    
            $userId = DB::table('emp_employees')->insertGetId($userData);
    
            // Return a successful response
            return response()->json([
                'status' => 'success',
                'message' => 'Employee added successfully',
                'data' => ['id' => $userId],
            ], 201);
        });
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
                    'user_status' => 'required|integer',
                    'marital_status' => 'nullable|string|max:20',
                    'user_image' => 'nullable|string|max:255',
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
                    'user_status' => $request->user_status,
                    'marital_status' => $request->marital_status,
                    'user_image' => $request->user_image,
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

                    'status' => $request->user_status,
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
        // Fetch company data
        $company = $this->common->commonGetById(1, 'id', 'com_companies', '*');
        
        // Fetch user data
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $users = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Combine the user data with company data
        $response = [
            'data' => [
                'user' => $users,
                'company' => $company
            ],
        ];

        // Return the combined data as JSON
        return response()->json($response, 200);
    }

    public function getEmployeeByUserId($id)
    {
        // Fetch user data
        $idColumn = 'user_id';
        $table = 'emp_employees';
        $fields = '*';
        $response = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Return the combined data as JSON
        return $response[0];
    }


}
