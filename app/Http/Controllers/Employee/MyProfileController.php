<?php

namespace App\Http\Controllers\Employee;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MyProfileController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee profile', ['only' => [
            'index',
            'getMyProfileById',

            ]]);
        $this->middleware('permission:update employee profile', ['only' => ['updateMyProfile']]);

        $this->common = new CommonModel();
    }


    public function index()
    {
        return view('employee.form');
    }


    // public function getMyProfileById()
    // {
    //     try {
    //         $userId = Auth::user()->id; // Get the logged-in user ID

    //         $table = 'emp_employees';
    //         $fields = [
    //             'emp_employees.*',
    //             'com_user_designations.name as designation_name',
    //             'loc_countries.name as country_name',
    //             'loc_provinces.name as province_name',
    //             'loc_cities.name as city_name',
    //             'com_currencies.name as currency_name',
    //             'roles.name as role_name',
    //         ];
    //         $joinArr = [
    //             'com_user_designations' => ['com_user_designations.id', '=', 'emp_employees.designation_id'],
    //             'loc_countries' => ['loc_countries.id', '=', 'emp_employees.country_id'],
    //             'loc_provinces' => ['loc_provinces.id', '=', 'emp_employees.province_id'],
    //             'loc_cities' => ['loc_cities.id', '=', 'emp_employees.city_id'],
    //             'com_currencies' => ['com_currencies.id', '=', 'emp_employees.currency_id'],
    //             'roles' => ['roles.id', '=', 'emp_employees.role_id'],
    //         ];

    //         $user = $this->common->commonGetById($userId, $table, $fields, $joinArr);

    //         if (!$user || count($user) === 0) {
    //             return response()->json(['status' => 'error', 'message' => 'Employee not found!'], 404);
    //         }

    //         return response()->json(['status' => 'success', 'data' => $user], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching logged-in user profile: ' . $e->getMessage());
    //         return response()->json(['status' => 'error', 'message' => 'Something went wrong!'], 500);
    //     }
    // }


    //==
    public function getMyProfileById()
    {
        try {
            $userId = Auth::user()->id; // Get the logged-in user ID

            $table = 'emp_employees';
            $fields = [
                'emp_employees.*',
                'com_user_designations.name as designation_name',
                'loc_countries.name as country_name',
                'loc_provinces.name as province_name',
                'loc_cities.name as city_name',
                'com_currencies.name as currency_name',
                'roles.name as role_name',
            ];
            $joinArr = [
                'com_user_designations' => ['com_user_designations.id', '=', 'emp_employees.designation_id'],
                'loc_countries' => ['loc_countries.id', '=', 'emp_employees.country_id'],
                'loc_provinces' => ['loc_provinces.id', '=', 'emp_employees.province_id'],
                'loc_cities' => ['loc_cities.id', '=', 'emp_employees.city_id'],
                'com_currencies' => ['com_currencies.id', '=', 'emp_employees.currency_id'],
                'roles' => ['roles.id', '=', 'emp_employees.role_id'],
            ];

            // Pass 'user_id' as the column name if the foreign key exists
            $user = $this->common->commonGetById($userId, 'user_id', $table, $fields, $joinArr);

            if (!$user || $user->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found!'], 404);
            }

            return response()->json(['status' => 'success', 'data' => $user->first()], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching logged-in user profile: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!'], 500);
        }
    }



    // public function createEmployee(Request $request)
    // {
    //     return DB::transaction(function () use ($request) {
    //         // Validate input fields
    //         $request->validate([
    //             'title' => 'required|string|max:20',
    //             'first_name' => 'required|string|max:100',
    //             'last_name' => 'required|string|max:100',
    //             'full_name' => 'nullable|string|max:255',
    //             'name_with_initials' => 'nullable|string|max:255',
    //             'address_1' => 'required|string|max:255',
    //             'address_2' => 'nullable|string|max:255',
    //             'address_3' => 'nullable|string|max:255',
    //             'nic' => 'required|string|max:255|unique:emp_employees,nic',
    //             'country_id' => 'required|integer',
    //             'province_id' => 'required|integer',
    //             'city_id' => 'required|integer',
    //             'postal_code' => 'nullable|string|max:25',
    //             'contact_1' => 'required|string|max:20',
    //             'contact_2' => 'nullable|string|max:20',
    //             'work_contact' => 'nullable|string|max:20',
    //             'home_contact' => 'nullable|string|max:20',
    //             'immediate_contact_person' => 'nullable|string|max:255',
    //             'immediate_contact_no' => 'nullable|string|max:20',
    //             'personal_email' => 'nullable|email|max:255|unique:emp_employees,personal_email',
    //             'work_email' => 'nullable|email|max:255|unique:emp_employees,work_email',
    //             'epf_reg_no' => 'nullable|string|max:255',
    //             'religion_id' => 'nullable|integer',
    //             'dob' => 'nullable|date',
    //             'gender' => 'nullable|string|max:10',
    //             'bond_period' => 'required',
    //             'user_status' => 'required|integer',
    //             'marital_status' => 'nullable|string|max:20',
    //             'user_image' => 'nullable|string|max:255',
    //             'branch_id' => 'required|integer',
    //             'department_id' => 'required|integer',
    //             'punch_machine_user_id' => 'nullable|integer',
    //             'designation_id' => 'required|integer',
    //             'user_group_id' => 'required|integer',
    //             'policy_group_id' => 'required|integer',
    //             'pay_period_schedule_id' => 'required|integer',
    //             'appointment_date' => 'required|date',
    //             'appointment_note' => 'nullable|string',
    //             'terminated_date' => 'nullable|date',
    //             'terminated_note' => 'nullable|string',
    //             'employment_type_id' => 'required|integer',
    //             'employment_time' => 'nullable|integer',
    //             'confirmed_date' => 'nullable|date',
    //             'resigned_date' => 'nullable|date',
    //             'retirement_date' => 'nullable|date',
    //             'currency_id' => 'nullable|integer',
    //             'pay_period_id' => 'nullable|integer',
    //             'permission_group_id' => 'nullable|string',
    //             'email' => 'required|email|max:255|unique:users,email',
    //             'password' => 'required|string|min:4|max:20', // Password validation
    //         ]);

    //         //===========================================================================================================
    //         // create user and user persmission
    //         //===========================================================================================================
    //         // Prepare user data and insert into the 'users' table
    //         $user = User::create([
    //             'name' => $request->full_name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         // Attach roles to the user
    //         $user->syncRoles([$request->permission_group_id]);
    //         //===========================================================================================================

    //         // Prepare user data and insert into the 'emp_employees' table
    //         $userData = [
    //             'user_id' => $user->id, // Use the newly created user ID
    //             'title' => $request->title,
    //             'first_name' => $request->first_name,
    //             'last_name' => $request->last_name,
    //             'full_name' => $request->full_name,
    //             'name_with_initials' => $request->name_with_initials,
    //             'address_1' => $request->address_1,
    //             'address_2' => $request->address_2,
    //             'address_3' => $request->address_3,
    //             'nic' => $request->nic,
    //             'country_id' => $request->country_id,
    //             'province_id' => $request->province_id,
    //             'city_id' => $request->city_id,
    //             'postal_code' => $request->postal_code,
    //             'contact_1' => $request->contact_1,
    //             'contact_2' => $request->contact_2,
    //             'work_contact' => $request->work_contact,
    //             'home_contact' => $request->home_contact,
    //             'immediate_contact_person' => $request->immediate_contact_person,
    //             'immediate_contact_no' => $request->immediate_contact_no,
    //             'personal_email' => $request->personal_email,
    //             'work_email' => $request->work_email,
    //             'epf_reg_no' => $request->epf_reg_no,
    //             'religion' => $request->religion_id,
    //             'dob' => $request->dob,
    //             'gender' => $request->gender,
    //             'bond_period' => $request->input('bond_period'), // Will now receive the value of 'months'
    //             'user_status' => $request->user_status,
    //             'marital_status' => $request->marital_status,
    //             'user_image' => $request->user_image,
    //             'punch_machine_user_id' => $request->punch_machine_user_id,
    //             'designation_id' => $request->designation_id,
    //             'user_group_id' => $request->user_group_id,
    //             'policy_group_id' => $request->policy_group_id,
    //             'appointment_date' => $request->appointment_date,
    //             'appointment_note' => $request->appointment_note,
    //             'terminated_date' => $request->terminated_date,
    //             'terminated_note' => $request->terminated_note,
    //             'employment_type_id' => $request->employment_type_id,
    //             'employment_time' => $request->employment_time,
    //             'confirmed_date' => $request->confirmed_date,
    //             'resigned_date' => $request->resigned_date,
    //             'retirement_date' => $request->retirement_date,
    //             'currency_id' => $request->currency_id,
    //             'pay_period_id' => $request->pay_period_id,
    //             'created_by' => Auth::id(),
    //             'updated_by' => Auth::id(),
    //         ];

    //         Log::info('User Data', $userData);

    //         $userId = DB::table('emp_employees')->insertGetId($userData);

    //         // Return a successful response
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Employee added successfully',
    //             'data' => ['id' => $userId],
    //         ], 201);
    //     });
    // }



    public function createEmployee(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Step 1: Validate input fields
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
                'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                'pay_period_id' => 'nullable|integer',
                'permission_group_id' => 'nullable|string',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:4|max:20',
                'doc_file' => 'nullable',
                'doc_title' => 'nullable|string|max:255',
                'doc_type_id' => 'nullable|integer',
            ]);

            // Step 2: Create a new user
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Attach roles to the user
            $user->syncRoles([$request->permission_group_id]);

            //==================================================
            // image upload
            //==================================================

            $imagePath = null;
            if ($request->hasFile('user_image')) {
                $imageResponse = $this->common->uploadImage(
                    $user->id, // Use user ID as image ID
                    $request->file('user_image'),
                    'uploads/employee/images', // Path for original image
                    'uploads/employee/thumbnails' // Path for thumbnail
                );

                $imageData = json_decode($imageResponse->getContent(), true);
                $imagePath = $imageData['data']['imageOrgPath'] . '/' . $imageData['data']['imageName'] . $imageData['data']['imageExtension'];
            }

            // Step 3: Insert employee data into emp_employees table
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
                'user_image' => $imagePath,
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
                'pay_period_id' => $request->pay_period_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            $userId = DB::table('emp_employees')->insertGetId($userData);

            // Step 4: Save branch and department data
            DB::table('com_branch_department_users')->insert([
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Step 5: Handle document upload
            if ($request->hasFile('doc_file')) {
                $docId = $userId; // Use the employee ID as the document ID
                $uploadDocPath = 'uploads/documents/employees'; // Define the upload directory
                $docFile = $request->file('doc_file');

                // Call uploadDocument and handle the response
                $documentResponse = $this->uploadDocument($docId, $docFile, $uploadDocPath);

                // Parse the response to check for success
                $documentData = json_decode($documentResponse->getContent(), true);
                if ($documentData['status'] === 'success') {
                    // Save the document details in the emp_documents table
                    DB::table('emp_documents')->insert([
                        'user_id' => $userId,
                        'doc_type_id' => $request->doc_type_id ?? null, // Handle nullable case
                        'title' => $request->doc_title ?? null,         // Handle nullable case
                        'file' => $documentData['data']['fileName'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    // Rollback and return an error if document upload fails
                    throw new \Exception('Document upload failed: ' . $documentData['message']);
                }
            }

            // Step 6: Return a successful response
            return response()->json([
                'status' => 'success',
                'message' => 'Employee added successfully',
                'data' => ['id' => $userId],
            ], 201);
        });
    }



}
