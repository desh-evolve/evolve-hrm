<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee profile', ['only' => ['employee_profile', 'getLoggedInUserProfile']]);
        $this->middleware('permission:view user', ['only' => [
            'employee_list',
            'emp_form',
            'showBankDetails',
            'showWageDetails',
            'showQualificationDetails',
            'showDocuments',
            'showWorkExperianceDetails',
            'showPromotionDetails',
            'showFamilyDetails',
            'showJobHistoryDetails',
            'showKpiDetails',
            'getAllEmployees',
            'getEmployeeByUserId',
            'getQualificationByEmployeeId',
            'getBankDetailsByEmployeeId',
            'getWorkExperienceByEmployeeId',
            'getJobHistoryByEmployeeId',
            'getKpiByEmployeeId',
            'getPromotionsByEmployeeId',
            'getDocumentsByEmployeeId',
            'getSingleEmployeeByUserId',
        ]]);

        $this->middleware('permission:create user', ['only' => ['employee_form', 'getEmployeeDropdownData', 'createEmployee', 'downloadDocumentLoadProfile']]);
        $this->middleware('permission:update user', ['only' => ['employee_form', 'getEmployeeDropdownData', 'updateEmployee']]);
        $this->middleware('permission:delete user', ['only' => ['deleteEmployee', 'deleteDocument']]);

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

    public function employee_profile($emp)
    {
        if($emp == 'my_profile'){
            $emp = Auth::id();
        }

        return view('employee.emp_profile', ['emp' => $emp]);
    }




    public function emp_form()
    {
        return view('employee.form');
    }

//========================================================================================
// Navigate to Employees' payroll
//========================================================================================

    public function showBankDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);


        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        // Fetch bank details associated with the user
        $bankDetails = $this->common->commonGetById($id, 'user_id', 'emp_bank_details', '*');

        // Pass the user and bank details to the view
        return view('employee_bank.edit', ['user' => $user[0], 'bankDetails' => $bankDetails, ]);
    }


    public function showWageDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);


        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        // Fetch wage details associated with the user
        $wageDetails = $this->common->commonGetById($id, 'user_id', 'emp_wage', '*');

        // Pass the user and wage details to the view
        return view('employee_wage.index', ['user' => $user[0], 'wageDetails' => $wageDetails, ]);
    }

//========================================================================================
// Navigate to Employees' (employee list)
//========================================================================================

    public function showQualificationDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        $qualificationDetails = $this->common->commonGetById($id, 'user_id', 'emp_qualifications', '*');

        return view('employee_qualification.index', ['user' => $user[0], 'qualificationDetails' => $qualificationDetails]);
    }


    public function showDocuments($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        $documents = $this->common->commonGetById($id, 'user_id', 'emp_documents', '*');

        return view('employee_documents.index', ['user' => $user[0], 'documents' => $documents]);
    }


    public function showWorkExperianceDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        $workDetails = $this->common->commonGetById($id, 'user_id', 'emp_work_experience', '*');

        return view('employee_work_experience.index', ['user' => $user[0], 'workExperianceDetails' => $workDetails]);
    }


    public function showPromotionDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        $promotionDetails = $this->common->commonGetById($id, 'user_id', 'emp_promotions', '*');

        return view('employee_promotion.index', ['user' => $user[0], 'promotionDetails' => $promotionDetails]);
    }


    public function showFamilyDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        $familyDetails = $this->common->commonGetById($id, 'user_id', 'emp_family', '*');

        return view('employee_family.index', ['user' => $user[0], 'familyDetails' => $familyDetails]);
    }


    public function showJobHistoryDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        $jobHistoryDetails = $this->common->commonGetById($id, 'user_id', 'emp_job_history', '*');

        return view('employee_job_history.index', ['user' => $user[0], 'jobHistoryDetails' => $jobHistoryDetails]);
    }


    public function showKpiDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);

        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        $kpiDetails = $this->common->commonGetById($id, 'user_id', 'emp_kpi', '*');


        return view('employee_kpi.index', ['user' => $user[0], 'kpiDetails' => $kpiDetails]);
    }

//========================================================================================
// Dropdown employee data
//========================================================================================

    public function getEmployeeDropdownData()
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
        $user_designations = $this->common->commonGetAll('com_user_designations', '*');
        $policy_groups = $this->common->commonGetAll('policy_group', '*');
        $user_status = $this->common->commonGetAll('user_status', '*');
        $currencies = $this->common->commonGetAll('com_currencies', '*');
        $pay_period = $this->common->commonGetAll(
            'pay_period_schedule',
            [
                'pay_period_schedule.id',
                'pay_period_schedule.name',
            ]);


        $roles = $this->common->commonGetAll('roles', '*', [], [], 'all');
        $religion = $this->common->commonGetAll('religion', '*');
        $employment_types = $this->common->commonGetAll('employment_types', '*');
        $countries = $this->common->commonGetAll('loc_countries', '*');
        $provinces = $this->common->commonGetAll('loc_provinces', '*');
        $cities = $this->common->commonGetAll('loc_cities', '*');
        $doc_types = $this->common->commonGetAll('com_employee_doc_types', '*');


        return response()->json([
            'data' => [
                'branches' => $branches,
                'departments' => $departments,
                'user_groups' => $user_groups,
                'user_designations' => $user_designations,
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

//========================================================================================
// Create
//========================================================================================

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
                'bond_period' => 'required',
                'user_status' => 'required|integer',
                'marital_status' => 'nullable|string|max:20',
                // 'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'branch_id' => 'required|integer',
                'department_id' => 'required|integer',
                'punch_machine_user_id' => 'nullable|integer',
                'designation_id' => 'required|integer',
                'user_group_id' => 'required|integer',
                'policy_group_id' => 'required|integer',
                'pay_period_schedule_id' => 'required|integer',
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
                'pay_period_schedule_id' => 'nullable|integer',
                'permission_group_id' => 'nullable|string',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:4|max:20',
                //document validation
                'doc_file' => 'sometimes|array',
                'doc_file.*' => 'sometimes|file|mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx|max:2048',
                'doc_title' => 'nullable|array|min:1',
                'doc_title.*' => 'nullable|string|max:255',
                'doc_type_id' => 'nullable|array|min:1',
                'doc_type_id.*' => 'nullable|integer',
            ]);

            // Create a new user
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Attach roles to the user
            $user->syncRoles([$request->permission_group_id]);

            // Insert employee data into `emp_employees` table
            $userData = [
                'user_id' => $user->id,
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
                // 'user_image' => $imagePath,
                'punch_machine_user_id' => $request->punch_machine_user_id,
                'designation_id' => $request->designation_id,
                'user_group_id' => $request->user_group_id,
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
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            $userId = DB::table('emp_employees')->insertGetId($userData);


            // Upload and Save Documents
            if ($request->hasFile('doc_file') && is_iterable($request->file('doc_file'))) {
                $uploadPath = 'uploads/employee/documents';

                foreach ($request->file('doc_file') as $key => $docFile) {
                    if (!isset($request->doc_type_id[$key]) || !isset($request->doc_title[$key])) {
                        continue;
                    }

                    // Upload Document
                    $uploadResponse = $this->common->uploadDocument($userId, $docFile, $uploadPath);

                    if ($uploadResponse['status'] !== 'success') {
                        Log::error("Document upload failed: " . $uploadResponse['message']);
                        continue;
                    }

                    // Save to emp_documents table
                    DB::table('emp_documents')->insert([
                        'user_id' => $user->id,
                        'doc_type_id' => $request->doc_type_id[$key],
                        'title' => $request->doc_title[$key],
                        'file' => $uploadResponse['data']['fileName'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }


            // Save branch and department data
            DB::table('com_branch_department_users')->insert([
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);


            // Save pay_period_shedule_user data
            DB::table('pay_period_schedule_user')->insert([
                'user_id' => $user->id,
                'pay_period_schedule_id' => $request->pay_period_schedule_id,
            ]);


            // Return a successful response
            return response()->json([
                'status' => 'success',
                'message' => 'Employee added successfully',
                'data' => [
                    'id' => $userId,
                    'user_id' => $user->id,
                ],
            ], 201);
        });
    }

//=============================================================================================================
// Update employee details
//=============================================================================================================

    public function updateEmployee(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Retrieve employee record to get user_id
                $employee = DB::table('emp_employees')->where('id', $id)->first();

                if (!$employee) {
                    return response()->json(['status' => 'error', 'message' => 'Employee not found.'], 404);
                }

                // Get user_id from emp_employees table
                $user_id = $employee->user_id;

                // Validate input data
                $request->validate([
                    'user_no' => 'required|integer',
                    'title' => 'required|string|max:20',
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'full_name' => 'nullable|string|max:255',
                    'name_with_initials' => 'nullable|string|max:255',
                    'address_1' => 'required|string|max:255',
                    'address_2' => 'nullable|string|max:255',
                    'address_3' => 'nullable|string|max:255',
                    'nic' => ['required', 'string', 'max:255', Rule::unique('emp_employees', 'nic')->ignore($id, 'id')],
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
                    'personal_email' => ['nullable', 'email', 'max:255', Rule::unique('emp_employees', 'personal_email')->ignore($id, 'id')],
                    'work_email' => ['nullable', 'email', 'max:255', Rule::unique('emp_employees', 'work_email')->ignore($id, 'id')],
                    'epf_reg_no' => 'nullable|string|max:255',
                    'religion_id' => 'nullable|integer',
                    'dob' => 'nullable|date',
                    'gender' => 'nullable|string|max:10',
                    'bond_period' => 'required',
                    'user_status' => 'required|integer',
                    'marital_status' => 'nullable|string|max:20',
                    'branch_id' => 'required|integer',
                    'department_id' => 'required|integer',
                    'punch_machine_user_id' => 'nullable|integer',
                    'designation_id' => 'required|integer',
                    'user_group_id' => 'required|integer',
                    'policy_group_id' => 'required|integer',
                    'pay_period_schedule_id' => 'required|integer',
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
                    'pay_period_schedule_id' => 'nullable|integer',
                    'permission_group_id' => 'nullable|string',
                    'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user_id, 'id')],
                ]);

                // Update employee details
                DB::table('emp_employees')->where('id', $id)->update([
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
                    'punch_machine_user_id' => $request->punch_machine_user_id,
                    'designation_id' => $request->designation_id,
                    'user_group_id' => $request->user_group_id,
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
                    'updated_by' => Auth::id(),
                ]);


                // Update user table
                DB::table('users')->where('id', $user_id)->update([
                    'name' => $request->full_name,
                    'email' => $request->email,
                ]);


                 // Update branch & department details
                DB::table('com_branch_department_users')->where('user_id', $user_id)->update([
                    'user_id' => $user_id,
                    'branch_id' => $request->branch_id,
                    'department_id' => $request->department_id,
                    'updated_by' => Auth::id(),
                ]);


                // Update pay_period_shedule_user details
                DB::table('pay_period_schedule_user')->where('user_id', $user_id)->update([
                    'user_id' => $user_id,
                    'pay_period_schedule_id' => $request->pay_period_schedule_id,
                ]);

                return response()->json(['status' => 'success', 'message' => 'Employee updated successfully', 'data' => ['id' => $id]], 200);

            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

//=============================================================================================================
// Delete employee
//=============================================================================================================

    //delete employee
    public function deleteEmployee($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employees';
        $table = 'emp_employees';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

//=============================================================================================================
// Get All Employee data
//=============================================================================================================

    public function getAllEmployees()
    {
        $table = 'emp_employees';
        $fields = '*';
        $user_designations = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $user_designations], 200);
    }

    // download document
    public function downloadDocumentLoadProfile($file)
    {
        $filePath = storage_path("app/public/uploads/employee/documents/" . $file);

        if (!file_exists($filePath)) {
            Log::error("File not found: " . $filePath);
            return response()->json(['status' => 'error', 'message' => 'File not found.'], 404);
        }

        return response()->download($filePath);
    }

//================================================================================================================
// Employee profile details
//================================================================================================================


    public function getEmployeeByUserId($id)
    {

        // Fetch company data
        $company = $this->common->commonGetById(1, 'id', 'com_companies', '*');

        // Fetch user data
        $idColumn = 'emp_employees.user_id';
        $table = 'emp_employees';
        $fields = [
            'emp_employees.*',
            'employment_types.name AS employment_types_name',
            'com_currencies.currency_name AS currency_name',
            'com_user_designations.emp_designation_name AS designation_name',
            'religion.name AS religion_name',
            'loc_countries.country_name AS country_name',
            'loc_provinces.province_name AS province_name',
            'loc_cities.city_name AS city_name',
            'com_employee_groups.emp_group_name AS user_group_name',
        ];

        $joinArr = [
            'com_user_designations' => ['com_user_designations.id', '=', 'emp_employees.designation_id'],
            'loc_countries' => ['loc_countries.id', '=', 'emp_employees.country_id'],
            'loc_provinces' => ['loc_provinces.id', '=', 'emp_employees.province_id'],
            'loc_cities' => ['loc_cities.id', '=', 'emp_employees.city_id'],
            'com_currencies' => ['com_currencies.id', '=', 'emp_employees.currency_id'],
            'religion' => ['religion.id', '=', 'emp_employees.religion'],
            'employment_types' => ['employment_types.id', '=', 'emp_employees.employment_type_id'],
            'com_employee_groups' => ['com_employee_groups.id', '=', 'emp_employees.user_group_id'],
        ];

        $connections = [
            'com_branch_department_users' => [
                'con_fields' => [
                    'com_branch_department_users.branch_id AS branch_id',
                    'com_branch_department_users.department_id AS department_id',
                    'com_branches.branch_name AS branch_name',
                    'com_departments.department_name AS department_name',
                ],
                'con_where' => [
                    'com_branch_department_users.user_id' => 'user_id', // Match `user_id` with employee data
                ],
                'con_joins' => [
                    'com_branches' => ['com_branches.id', '=', 'com_branch_department_users.branch_id'],
                    'com_departments' => ['com_departments.id', '=', 'com_branch_department_users.department_id'],
                ],
                'con_name' => 'branch_department_details',
                'except_deleted' => true,
            ],

            'model_has_roles' => [
                'con_fields' => [
                    'model_has_roles.role_id AS role_id',
                    'model_has_roles.model_id AS user_id',
                    'roles.name AS role_name',
                ],
                'con_where' => [
                    'model_has_roles.model_id' => 'user_id', // Match `user_id` with employee data
                ],
                'con_joins' => [
                    'roles' => ['roles.id', '=', 'model_has_roles.role_id'],
                ],
                'con_name' => 'role_details',
                'except_deleted' => 'all', // Removed the `status` condition
            ],

            'pay_period_schedule_user' => [
                'con_fields' => [
                    'pay_period_schedule_user.pay_period_schedule_id AS pay_period_schedule_id',
                    'pay_period_schedule_user.user_id AS user_id',
                    'pay_period_schedule.name AS pay_period_schedule_name',
                ],
                'con_where' => [
                    'pay_period_schedule_user.user_id' => 'user_id', // Match `user_id` with employee data
                ],
                'con_joins' => [
                    'pay_period_schedule' => ['pay_period_schedule.id', '=', 'pay_period_schedule_user.pay_period_schedule_id'],
                ],
                'con_name' => 'pay_period_schedule_details',
                'except_deleted' => true,
            ],
        ];

        $users = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr, [], true, $connections);

        $response = [
            'data' => [
                'user' => $users,
                'company' => $company,
            ],
        ];

        return response()->json($response, 200);
    }


    public function getSingleEmployeeByUserId($id)
    {
        $idColumn = 'user_id';
        $table = 'emp_employees';
        $fields = '*';

        $connections = [
            'com_branch_department_users' => [
                'con_fields' => ['*'],
                'con_where' => ['com_branch_department_users.user_id' => 'user_id', ],
                'con_joins' => [],
                'con_name' => 'branch_department_details',
                'except_deleted' => true,
            ],

            'users' => [
                'con_fields' => ['*'],
                'con_where' => ['users.id' => 'user_id', ],
                'con_joins' => [],
                'con_name' => 'email_details',
                'except_deleted' => 'all',
            ],

            'model_has_roles' => [
                'con_fields' => ['*'],
                'con_where' => ['model_has_roles.model_id' => 'user_id', ],
                'con_joins' => ['roles' => ['roles.id', '=', 'model_has_roles.role_id'],],
                'con_name' => 'role_details',
                'except_deleted' => 'all', // Removed the `status` condition
            ],

            'emp_documents' => [
                'con_fields' => ['*'],
                'con_where' => ['emp_documents.user_id' => 'user_id', ],
                'con_joins' => [],
                'con_name' => 'document_details',
                'except_deleted' => true,
            ],

            'pay_period_schedule_user' => [
                'con_fields' => ['*'],
                'con_where' => ['pay_period_schedule_user.user_id' => 'user_id', ],
                'con_joins' => [],
                'con_name' => 'pay_period_schedule_details',
                'except_deleted' => true,
            ],
        ];

        $users = $this->common->commonGetById($id, $idColumn, $table, $fields, [], [], true, $connections);

        return response()->json(['status' => 'success', 'data' => $users], 200);
    }


    public function getQualificationByEmployeeId($id)
    {
        try {
            // Find the user_id associated with the given emp_id
            $employee = DB::table('emp_employees')
                ->where('id', $id)
                ->select('user_id')
                ->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
            }

            $user_id = $employee->user_id;

            $idColumn = 'user_id';
            $table = 'emp_qualifications';
            $fields = '*';
            $qualificationDetails = $this->common->commonGetById($user_id, $idColumn, $table, $fields);

            return response()->json(['status' => 'success', 'data' => $qualificationDetails], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error fetching documents', 'error' => $e->getMessage()], 500);
        }
    }


    public function getBankDetailsByEmployeeId($id)
    {
        try {
            // Find the user_id associated with the given emp_id
            $employee = DB::table('emp_employees')
                ->where('id', $id)
                ->select('user_id')
                ->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
            }

            $user_id = $employee->user_id;

            $idColumn = 'user_id';
            $table = 'emp_bank_details';
            $fields = '*';
            $bankDetails = $this->common->commonGetById($user_id, $idColumn, $table, $fields);

            return response()->json(['status' => 'success', 'data' => $bankDetails], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error fetching documents', 'error' => $e->getMessage()], 500);
        }
    }


    public function getWorkExperienceByEmployeeId($id)
    {
        try {
            // Find the user_id associated with the given emp_id
            $employee = DB::table('emp_employees')
                ->where('id', $id)
                ->select('user_id')
                ->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
            }

            $user_id = $employee->user_id;

            $idColumn = 'user_id';
            $table = 'emp_work_experience';
            $fields = '*';
            $workExperience = $this->common->commonGetById($user_id, $idColumn, $table, $fields);

            return response()->json(['status' => 'success', 'data' => $workExperience], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error fetching documents', 'error' => $e->getMessage()], 500);
        }
    }


    public function getPromotionsByEmployeeId($id)
    {
        try {
            // Find the user_id associated with the given emp_id
            $employee = DB::table('emp_employees')
                ->where('id', $id)
                ->select('user_id')
                ->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
            }

            $user_id = $employee->user_id;

            $idColumn = 'user_id';
            $table = 'emp_promotions';
            $fields = '*';
            $promotions = $this->common->commonGetById($user_id, $idColumn, $table, $fields);

            return response()->json(['status' => 'success', 'data' => $promotions], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error fetching documents', 'error' => $e->getMessage()], 500);
        }
    }


    public function getJobHistoryByEmployeeId($id)
    {
        try {
            // Find the user_id associated with the given emp_id
            $employee = DB::table('emp_employees')
                ->where('id', $id)
                ->select('user_id')
                ->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
            }

            $user_id = $employee->user_id;

            $idColumn = 'user_id';
            $table = 'emp_job_history';
            $fields = '*';
            $joinArr = [
                'com_branches'=>['com_branches.id', '=', 'emp_job_history.branch_id'],
                'com_departments'=>['com_departments.id', '=', 'emp_job_history.department_id'],
                'com_user_designations'=>['com_user_designations.id', '=', 'emp_job_history.designation_id'],
            ];
            $jobHistory = $this->common->commonGetById($user_id, $idColumn, $table, $fields, $joinArr);

            return response()->json(['status' => 'success', 'data' => $jobHistory], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error fetching documents', 'error' => $e->getMessage()], 500);
        }
    }


    public function getKpiByEmployeeId($id)
    {
        try {
            // Find the user_id associated with the given emp_id
            $employee = DB::table('emp_employees')
                ->where('id', $id)
                ->select('user_id')
                ->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
            }

            $user_id = $employee->user_id;

            $idColumn = 'user_id';
            $table = 'emp_kpi';
            $fields = '*';

            $kpi = $this->common->commonGetById($user_id, $idColumn, $table, $fields);

            return response()->json(['status' => 'success', 'data' => $kpi], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error fetching documents', 'error' => $e->getMessage()], 500);
        }
    }


    public function getDocumentsByEmployeeId($id)
    {
        try {
            // Find the user_id associated with the given emp_id
            $employee = DB::table('emp_employees')
                ->where('id', $id)
                ->select('user_id')
                ->first();

            if (!$employee) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
            }

            $user_id = $employee->user_id;

            $idColumn = 'user_id';
            $table = 'emp_documents';
            $fields = '*';
            $joinArr = [
                'com_employee_doc_types'=>['com_employee_doc_types.id', '=', 'emp_documents.doc_type_id'],
            ];
            $documents = $this->common->commonGetById($user_id, $idColumn, $table, $fields, $joinArr);

            return response()->json(['status' => 'success', 'data' => $documents], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error fetching documents', 'error' => $e->getMessage()], 500);
        }
    }



}
