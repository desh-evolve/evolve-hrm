<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;

// controller
use App\Http\Controllers\Company\IndustryController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\LocationController;
use App\Http\Controllers\Company\BranchController;
use App\Http\Controllers\Company\DepartmentController;
use App\Http\Controllers\Company\EmployeeDesignationController;
use App\Http\Controllers\Company\EmployeeGroupController;
use App\Http\Controllers\Company\WageGroupController;
use App\Http\Controllers\Company\CurrencyController;
use App\Http\Controllers\Company\BranchBankDetailsController;
use App\Http\Controllers\Company\HierarchyController;

// use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeQualificationController;
use App\Http\Controllers\Employee\EmployeeBankDetailsController;
use App\Http\Controllers\Employee\JobHistoryController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeWorkExperienceController;
use App\Http\Controllers\Employee\EmployeePromotionController;
use App\Http\Controllers\Employee\EmployeeFamilyController;
use App\Http\Controllers\Employee\EmpWageController;
use App\Http\Controllers\Employee\EmployeeMessagesController;
use App\Http\Controllers\Employee\UserPreferenceController;

// policies
use App\Http\Controllers\Policy\RoundingPolicyController;
use App\Http\Controllers\Policy\MealPolicyController;
use App\Http\Controllers\Policy\BreakPolicyController;
use App\Http\Controllers\Policy\AccrualPolicyController;
use App\Http\Controllers\Policy\ExceptionPolicyController;
use App\Http\Controllers\Policy\OvertimePolicyController;
use App\Http\Controllers\Policy\PremiumPolicyController;
use App\Http\Controllers\Policy\HolidayPolicyController;
use App\Http\Controllers\Policy\AbsencePolicyController;
use App\Http\Controllers\Policy\SchedulePolicyController;
use App\Http\Controllers\Policy\PolicyGroupsController;

// Attendance
use App\Http\Controllers\Attendance\PunchController;
use App\Http\Controllers\Attendance\MassPunchController;
use App\Http\Controllers\Attendance\TimeSheetController;
use App\Http\Controllers\Attendance\LeavesController;
use App\Http\Controllers\Attendance\AttendanceRequestsController;

// Payroll
use App\Http\Controllers\Payroll\PayStubAccountController;
use App\Http\Controllers\Payroll\PayStubAmendmentController;
use App\Http\Controllers\Payroll\PayPeriodScheduleController;
use App\Http\Controllers\Payroll\CompanyDeductionController;
use App\Http\Controllers\Payroll\PayStubEntryAccountLinkController;
use App\Http\Controllers\Policy\CommonPolicyController;


// Reports
use App\Http\Controllers\Reports\EmployeeDetailReportController;

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [AuthenticatedSessionController::class, 'create']);

/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/
//==============================================================================================================================
// Dashboard (temp)
//==============================================================================================================================

// Main Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/temp', [DashboardController::class, 'index'])->name('dashboard.temp.index');

Route::get('/dashboard/count/employee', [DashboardController::class, 'getAllEmployeeCount'])->name('dashboard.employee.count');
Route::get('/dashboard/count/leave', [DashboardController::class, 'getAllLeaveCount'])->name('dashboard.leave.count');
Route::get('/dashboard/messages', [DashboardController::class, 'getNewMessages'])->name('dashboard.new.messages');
Route::get('/dashboard/requests', [DashboardController::class, 'getRequestsData'])->name('dashboard.requests');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/preferences', [ProfileController::class, 'preferences'])->name('profile.preferences');
});

Route::group(['middleware' => ['role:super-admin|admin']], function () {

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);

    //==============================================================================================================================
    // Industry
    //==============================================================================================================================
    Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
    Route::post('/industries/create', [IndustryController::class, 'create'])->name('industries.create');
    Route::put('/industries/update/{id}', [IndustryController::class, 'update'])->name('industries.update');
    Route::get('/industries/edit', [IndustryController::class, 'edit'])->name('industries.edit');
    Route::delete('/industries/delete/{id}', [IndustryController::class, 'delete'])->name('industries.delete');
    Route::get('/industries/{id}', [IndustryController::class, 'show'])->name('industries.show');

    //==============================================================================================================================
    // Location
    //==============================================================================================================================
    // Location index
    Route::get('/location', [LocationController::class, 'index'])->name('location.index');

    // Country routes
    Route::post('/location/country/create', [LocationController::class, 'createCountry'])->name('location.country.create');
    Route::put('/location/country/update/{id}', [LocationController::class, 'updateCountry'])->name('location.country.update');
    Route::delete('/location/country/delete/{id}', [LocationController::class, 'deleteCountry'])->name('location.country.delete');
    Route::get('/location/countries', [LocationController::class, 'getAllCountries'])->name('location.countries.all');
    Route::get('/location/country/{id}', [LocationController::class, 'getCountryByCountryId'])->name('location.country.getById');

    // Province routes
    Route::post('/location/province/create', [LocationController::class, 'createProvince'])->name('location.province.create');
    Route::put('/location/province/update/{id}', [LocationController::class, 'updateProvince'])->name('location.province.update');
    Route::delete('/location/province/delete/{id}', [LocationController::class, 'deleteProvince'])->name('location.province.delete');
    Route::get('/location/provinces', [LocationController::class, 'getAllProvinces'])->name('location.provinces.all');
    Route::get('/location/province/{id}', [LocationController::class, 'getProvinceByProvinceId'])->name('location.province.getById');
    Route::get('/location/provinces/{countryId}', [LocationController::class, 'getProvincesByCountryId'])->name('location.province.getByCountryId');

    // City routes
    Route::post('/location/city/create', [LocationController::class, 'createCity'])->name('location.city.create');
    Route::put('/location/city/update/{id}', [LocationController::class, 'updateCity'])->name('location.city.update');
    Route::delete('/location/city/delete/{id}', [LocationController::class, 'deleteCity'])->name('location.city.delete');

    Route::get('/location/city/{id}', [LocationController::class, 'getCityByCityId'])->name('location.city.getById');
    Route::get('/location/cities/{provinceId}', [LocationController::class, 'getCitiesByProvinceId'])->name('location.city.getByProvinceId');

    //==============================================================================================================================
    // Branches
    //==============================================================================================================================
    // Branch routes
    Route::get('/company/branch', [BranchController::class, 'index'])->name('company.branch.index');
    Route::get('/company/branch/dropdown', [BranchController::class, 'getBranchDropdownData'])->name('company.branch.dropdown');
    Route::post('/company/branch/create', [BranchController::class, 'createBranch'])->name('company.branch.create');
    Route::put('/company/branch/update/{id}', [BranchController::class, 'updateBranch'])->name('company.branch.update');
    Route::delete('/company/branch/delete/{id}', [BranchController::class, 'deleteBranch'])->name('company.branch.delete');
    Route::get('/company/branches', [BranchController::class, 'getAllBranches'])->name('company.branches.all');
    Route::get('/company/branch/{id}', [BranchController::class, 'getBranchByBranchId'])->name('company.branch.getById');


    //==============================================================================================================================
    // Hierarchy
    //==============================================================================================================================
    Route::get('/company/hierarchy', [HierarchyController::class, 'index'])->name('company.hierarchy.index');
    Route::get('/company/hierarchy/form', [HierarchyController::class, 'form'])->name('company.hierarchy.form');
    Route::post('/company/hierarchy/create', [HierarchyController::class, 'createHierarchy'])->name('company.hierarchy.create');
    Route::put('/company/hierarchy/update/{id}', [HierarchyController::class, 'updateHierarchy'])->name('company.hierarchy.update');
    Route::delete('/company/hierarchy/delete/{id}', [HierarchyController::class, 'deleteHierarchy'])->name('company.hierarchy.delete');
    Route::get('/company/hierarchies', [HierarchyController::class, 'getAllHierarchy'])->name('company.hierarchy.all');
    Route::get('/company/hierarchy/dropdown', [HierarchyController::class, 'getHierarchyDropdownData'])->name('company.hierarchy.dropdown');
    Route::get('/company/hierarchy/{id}', [HierarchyController::class, 'getHierarchyById'])->name('company.hierarchy.getById');

    //==============================================================================================================================
    // Branch Bank Details
    //==============================================================================================================================

    Route::get('/branch/bank/details/{id}', [BranchBankDetailsController::class, 'index'])->name('branch.bank.details');
    Route::post('/branch/bank/create', [BranchBankDetailsController::class, 'createBankDetails'])->name('branch.bank.create');
    Route::put('/branch/bank/update/{id}', [BranchBankDetailsController::class, 'updateBankDetails'])->name('branch.bank.update');
    Route::delete('/branch/bank/delete/{id}', [BranchBankDetailsController::class, 'deleteBankDetails'])->name('branch.bank.delete');
    Route::get('/branch/bank/{id}', [BranchBankDetailsController::class, 'getBankDetailsByBanchId'])->name('branch.bank.getById');
    Route::get('/branch/single_bankdetail/{id}', [BranchBankDetailsController::class, 'getBankDetailsSingleBranch'])->name('branch.bank.single');

    //==============================================================================================================================
    // Departments
    //==============================================================================================================================
    // Departments routes
    Route::get('/company/department', [DepartmentController::class, 'index'])->name('company.department.index');
    Route::get('/company/department/employees', [DepartmentController::class, 'employees'])->name('company.department.employees');

    Route::get('/company/department/dropdown', [DepartmentController::class, 'getDepartmentDropdownData'])->name('company.department.dropdown');
    Route::post('/company/department/create', [DepartmentController::class, 'createDepartment'])->name('company.department.create');
    Route::put('/company/department/update/{id}', [DepartmentController::class, 'updateDepartment'])->name('company.department.update');
    Route::delete('/company/department/delete/{id}', [DepartmentController::class, 'deleteDepartment'])->name('company.department.delete');
    Route::get('/company/departments', [DepartmentController::class, 'getAllDepartments'])->name('company.departments.all');
    Route::get('/company/department/{id}', [DepartmentController::class, 'getDepartmentByDepartmentId'])->name('company.department.getById');

    // Department employees routes
    Route::get('/company/department/employees/dropdown', [DepartmentController::class, 'getDepartmentEmployeesDropdownData'])->name('company.department.employees.dropdown');
    Route::post('/company/department/employees/create', [DepartmentController::class, 'createDepartmentEmployees'])->name('company.department.employees.create');
    Route::get('/company/{branch_id}/department/{department_id}/employees', [DepartmentController::class, 'getDepartmentBranchEmployees'])->name('company.department.getAll');
    Route::delete('/company/department/employee/delete/{department_id}/{branch_id}/{employee_id}', [DepartmentController::class, 'deleteDepartmentBranchEmployees'])->name('company.department.employee.delete');

    //==============================================================================================================================
    // Employee Designation
    //==============================================================================================================================
    Route::get('/company/employee_designation', [EmployeeDesignationController::class, 'index'])->name('company.employee_designation.index');
    Route::post('/company/employee_designation/create', [EmployeeDesignationController::class, 'createEmployeeDesignation'])->name('company.employee_designation.create');
    Route::put('/company/employee_designation/update/{id}', [EmployeeDesignationController::class, 'updateEmployeeDesignation'])->name('company.employee_designation.update');
    Route::delete('/company/employee_designation/delete/{id}', [EmployeeDesignationController::class, 'deleteEmployeeDesignation'])->name('company.employee_designation.delete');
    Route::get('/company/employee_designations', [EmployeeDesignationController::class, 'getAllEmployeeDesignations'])->name('company.employee_designation.all');
    Route::get('/company/employee_designation/{id}', [EmployeeDesignationController::class, 'getEmployeeDesignationById'])->name('company.employee_designation.getById');

    //==============================================================================================================================
    // Employee Groups
    //==============================================================================================================================
    Route::get('/company/employee_group', [EmployeeGroupController::class, 'index'])->name('company.employee_group.index');

    Route::post('/company/employee_group/create', [EmployeeGroupController::class, 'createEmployeeGroup'])->name('company.employee_group.create');
    Route::put('/company/employee_group/update/{id}', [EmployeeGroupController::class, 'updateEmployeeGroup'])->name('company.employee_group.update');
    Route::delete('/company/employee_group/delete/{id}', [EmployeeGroupController::class, 'deleteEmployeeGroup'])->name('company.employee_group.delete');
    Route::get('/company/employee_groups', [EmployeeGroupController::class, 'getAllEmployeeGroups'])->name('company.employee_group.all');
    Route::get('/company/employee_group/{id}', [EmployeeGroupController::class, 'getEmployeeGroupById'])->name('company.employee_group.getById');

    //==============================================================================================================================
    // Wage Groups
    //==============================================================================================================================
    Route::get('/company/wagegroups', [WageGroupController::class, 'wageGroup'])->name('company.wagegroups.index');
    Route::post('/company/wagegroups/create', [WageGroupController::class, 'createWageGroups'])->name('company.wagegroups.create');
    Route::put('/company/wagegroups/update/{id}', [WageGroupController::class, 'updateWageGroups'])->name('company.wagegroups.update');
    Route::delete('/company/wagegroups/delete/{id}', [WageGroupController::class, 'deleteWageGroups'])->name('company.wagegroups.delete');
    Route::get('/company/allwagegroups', [WageGroupController::class, 'getAllWageGroups'])->name('company.wagegroups.all');
    Route::get('/company/wagegroups/{id}', [WageGroupController::class, 'getWageGroupById'])->name('company.wagegroups.getById');

    //==============================================================================================================================
    // Employee Bank Details
    //==============================================================================================================================
    Route::get('/employee/bank', [EmployeeBankDetailsController::class, 'index'])->name('employee.bank.index');
    Route::post('/employee/bank/create', [EmployeeBankDetailsController::class, 'createBankDetails'])->name('employee.bank.create');
    Route::put('/employee/bank/update/{id}', [EmployeeBankDetailsController::class, 'updateBankDetails'])->name('employee.bank.update');
    Route::delete('/employee/bank/delete/{id}', [EmployeeBankDetailsController::class, 'deleteBankDetails'])->name('employee.bank.delete');
    Route::get('/employee/bank/{id}', [EmployeeBankDetailsController::class, 'getBankDetailsByEmpId'])->name('employee.bank.getById');
    Route::get('/company/allemplyee', [EmployeeBankDetailsController::class, 'getAllEmployee'])->name('company.employee.all');

     //==============================================================================================================================
    // User Preference
    //==============================================================================================================================
    Route::get('/employee/user_preference', [UserPreferenceController::class, 'index'])->name('employee.user_preference');
    Route::get('/employee/user_preference/form', [UserPreferenceController::class, 'form'])->name('company.user_preference.form');
    Route::post('/employee/user_preference/create', [UserPreferenceController::class, 'createUserPreference'])->name('company.user_preference.create');
    Route::put('/employee/user_preference/update/{id}', [UserPreferenceController::class, 'updateUserPreference'])->name('company.user_preference.update');
    Route::delete('/employee/user_preference/delete/{id}', [UserPreferenceController::class, 'deleteUserPreference'])->name('company.user_preference.delete');
    Route::get('/employee/user_preferenceses', [UserPreferenceController::class, 'getAllUserPreference'])->name('company.user_preference.all');
    Route::get('/employee/user_preference/dropdown', [UserPreferenceController::class, 'getUserPreferenceDropdownData'])->name('company.user_preference.dropdown');
    Route::get('/employee/user_preference_by_id', [UserPreferenceController::class, 'getUserPreferenceById'])->name('company.user_preference.getById');


    //==============================================================================================================================
    // Employee Messages
    //==============================================================================================================================

    Route::get('/employee/messages', [EmployeeMessagesController::class, 'index'])->name('employee.messages.index');
    Route::get('/employee/allmessages', [EmployeeMessagesController::class, 'getAllMessages'])->name('employee.messages.all');
    Route::get('/employee/messages/{id}', [EmployeeMessagesController::class, 'getMessagesByControlId'])->name('employee.messages.getById');
    Route::get('/employee/name/dropdown', [EmployeeMessagesController::class, 'getEmployeeDropdownData'])->name('employee.name.dropdown');
    Route::post('/employee/messages/create', [EmployeeMessagesController::class, 'createSendMessage'])->name('employee.messages.create');
    Route::get('/employee/single_message/{id}', [EmployeeMessagesController::class, 'getMessagesBySingleId'])->name('employee.messages.single');
    Route::post('/employee/messages/reply', [EmployeeMessagesController::class, 'createReplyMessage'])->name('employee.messages.reply');
    Route::post('/employee/messages/mark-as-read', [EmployeeMessagesController::class, 'updateReadStatus'])->name('employee.messages.read');
    Route::get('/employee/sent/messages', [EmployeeMessagesController::class, 'getSentMessages'])->name('employee.messages.sent');
    Route::get('/employee/inbox/messages', [EmployeeMessagesController::class, 'getReceivedMessages'])->name('employee.messages.inbox');
    Route::delete('/employee/message/delete/{id}', [EmployeeMessagesController::class, 'deleteMessage'])->name('employee.message.delete');


    //==============================================================================================================================
    // Currencies
    //==============================================================================================================================
    Route::get('/company/currency', [CurrencyController::class, 'index'])->name('company.currency.index');
    Route::post('/company/currency/create', [CurrencyController::class, 'createCurrency'])->name('company.currency.create');
    Route::put('/company/currency/update/{id}', [CurrencyController::class, 'updateCurrency'])->name('company.currency.update');
    Route::delete('/company/currency/delete/{id}', [CurrencyController::class, 'deleteCurrency'])->name('company.currency.delete');
    Route::get('/company/allcurrency', [CurrencyController::class, 'getAllCurrency'])->name('company.currency.all');
    Route::get('/company/currency/{id}', [CurrencyController::class, 'getCurrencyById'])->name('company.currency.getById');


    //==============================================================================================================================
    // Employee Qualification
    //==============================================================================================================================
    Route::get('/employee/qualification', [EmployeeQualificationController::class, 'index'])->name('company.employee_qualification.index');
    Route::post('/employee/qualification/create', [EmployeeQualificationController::class, 'createEmployeeQualification'])->name('company.employee_qualification.create');
    Route::put('/employee/qualification/update/{id}', [EmployeeQualificationController::class, 'updateEmployeeQualification'])->name('company.employee_qualification.update');
    Route::delete('/employee/qualification/delete/{id}', [EmployeeQualificationController::class, 'deleteEmployeeQualification'])->name('company.employee_qualification.delete');
    Route::get('/employee/single_qualification/{id}', [EmployeeQualificationController::class, 'getSingleEmployeeQualification'])->name('company.employee_qualification.single');
    Route::get('/employee/qualification/dropdown', [EmployeeQualificationController::class, 'getEmployeeList'])->name('company.employee_qualification.dropdown');
    Route::get('/employee/qualification/{id}', [EmployeeQualificationController::class, 'getEmployeeQualificationById'])->name('company.employee_qualification.getById');

    // Employee employee work experience
    Route::get('/employee/work_experience', [EmployeeWorkExperienceController::class, 'index'])->name('company.employee_work_experience.index');
    Route::post('/employee/work_experience/create', [EmployeeWorkExperienceController::class, 'createEmployeeWorkExperience'])->name('company.employee_work_experience.create');
    Route::put('/employee/work_experience/update/{id}', [EmployeeWorkExperienceController::class, 'updateEmployeeWorkExperience'])->name('company.employee_work_experience.update');
    Route::delete('/employee/work_experience/delete/{id}', [EmployeeWorkExperienceController::class, 'deleteEmployeeWorkExperience'])->name('company.employee_work_experience.delete');
    Route::get('/employee/single_work_experience/{id}', [EmployeeWorkExperienceController::class, 'getSingleEmployeeWorkExperience'])->name('company.employee_work_experience.single');
    Route::get('/employee/work_experience/dropdown', [EmployeeWorkExperienceController::class, 'getEmployeeList'])->name('company.employee_work_experience.dropdown');
    Route::get('/employee/work_experience/{id}', [EmployeeWorkExperienceController::class, 'getEmployeeWorkExperienceById'])->name('company.employee_work_experience.getById');

    // Employee employee promotion
    Route::get('/employee/promotion', [EmployeePromotionController::class, 'index'])->name('company.employee_promotion.index');
    Route::post('/employee/promotion/create', [EmployeePromotionController::class, 'createEmployeePromotion'])->name('company.employee_promotion.create');
    Route::put('/employee/promotion/update/{id}', [EmployeePromotionController::class, 'updateEmployeePromotion'])->name('company.employee_promotion.update');
    Route::delete('/employee/promotion/delete/{id}', [EmployeePromotionController::class, 'deleteEmployeePromotion'])->name('company.employee_promotion.delete');
    Route::get('/employee/single_promotion/{id}', [EmployeePromotionController::class, 'getSingleEmployeePromotion'])->name('company.employee_promotion.single');
    Route::get('/employee/promotion/dropdown', [EmployeePromotionController::class, 'getPromoEmployeeList'])->name('company.employee_promotion.dropdown');
    Route::get('/employee/promotion/{id}', [EmployeePromotionController::class, 'getEmployeePromotionById'])->name('company.employee_promotion.getById');


    // Employee employee promotion
    Route::get('/employee/family', [EmployeeFamilyController::class, 'index'])->name('company.employee_family.index');
    Route::post('/employee/family/create', [EmployeeFamilyController::class, 'createEmployeeFamily'])->name('company.employee_family.create');
    Route::put('/employee/family/update/{id}', [EmployeeFamilyController::class, 'updateEmployeeFamily'])->name('company.employee_family.update');
    Route::delete('/employee/family/delete/{id}', [EmployeeFamilyController::class, 'deleteEmployeeFamily'])->name('company.employee_family.delete');
    Route::get('/employee/single_employee_family/{id}', [EmployeeFamilyController::class, 'getSingleEmployeeFamily'])->name('company.employee_family.single');
    Route::get('/employee/family/dropdown', [EmployeeFamilyController::class, 'getEmployeeList'])->name('company.employee_family.dropdown');
    Route::get('/employee/family/{id}', [EmployeeFamilyController::class, 'getEmployeeFamilyById'])->name('company.employee_family.getById');

    //==============================================================================================================================
    // Attendance
    //==============================================================================================================================

    //   Employee request
    Route::get('/attendance/request/index', [AttendanceRequestsController::class, 'index'])->name('request.index');
    Route::get('/attendance/request/dropdown', [AttendanceRequestsController::class, 'getRequestDropdownData'])->name('request.dropdown');
    Route::post('/attendance/request/create', [AttendanceRequestsController::class, 'createAttendenceRequests'])->name('request.create');
    Route::get('/attendance/request/{id}', [AttendanceRequestsController::class, 'getRequestsByControlId'])->name('request.getById');
    Route::delete('/attendance/request/delete/{id}', [AttendanceRequestsController::class, 'deleteAttendenceRequests'])->name('request.delete');

    //   Employee Punch
    Route::get('/company/employee_punch/index', [PunchController::class, 'index'])->name('company.employee_punch.index');
    Route::get('/company/employee_punch/dropdown', [PunchController::class, 'getDropdownData'])->name('company.employee_punch.dropdown');
    Route::get('/company/employee_punch/get_employees', [PunchController::class, 'getAllEmployees'])->name('company.employee_punch.get_employees');
    Route::post('/company/employee_punch/create', [PunchController::class, 'createEmployeePunch'])->name('company.employee_punch.create');
    Route::get('/company/employee_punch/{id}', [PunchController::class, 'getEmployeePunchById'])->name('company.employee_punch.getById');
    Route::get('/company/single_employee_punch/{id}', [PunchController::class, 'getSingleEmployeePunch'])->name('company.employee_punch.single');
    Route::put('/company/employee_punch/update/{id}', [PunchController::class, 'updateEmployeePunch'])->name('company.employee_punch.update');

    // Mass Punch
    Route::get('/company/mass_punch/index', [MassPunchController::class, 'index'])->name('company.mass_punch.index');
    Route::get('/company/mass_punch/dropdown', [MassPunchController::class, 'getDropdownData'])->name('company.mass_punch.dropdown');
    Route::post('/company/mass_punch/create', [MassPunchController::class, 'createMassPunch'])->name('company.mass_punch.create');
    Route::get('/company/mass_punch/list', [MassPunchController::class, 'showMassPunchList'])->name('company.mass_punch.mass_punch_list');

    // Timesheet
    Route::get('/employee/timesheet', [TimeSheetController::class, 'index'])->name('employee.timesheet');
    Route::get('/employee/timesheet/dropdown', [TimeSheetController::class, 'getDropdownData']);
    
    // Leaves
    Route::get('/employee/apply_leaves', [LeavesController::class, 'form'])->name('employee.apply_leaves');
    Route::post('/employee/apply_leaves/create', [LeavesController::class, 'create']);
    

    //==============================================================================================================================
    // Payroll
    //==============================================================================================================================

    // Pay stub account
    Route::get('/payroll/pay_stub_account', [PayStubAccountController::class, 'index'])->name('payroll.pay_stub_account');
    Route::post('/payroll/pay_stub_account/create', [PayStubAccountController::class, 'createPayStubAccount'])->name('payroll.pay_stub_account.create');
    Route::put('/payroll/pay_stub_account/update/{id}', [PayStubAccountController::class, 'updatePayStubAccount'])->name('payroll.pay_stub_account.update');
    Route::delete('/payroll/pay_stub_account/delete/{id}', [PayStubAccountController::class, 'deletePayStubAccount'])->name('payroll.pay_stub_account.delete');
    Route::get('/payroll/pay_stub_account/allPayStubAccount', [PayStubAccountController::class, 'getAllPayStubAccount'])->name('payroll.pay_stub_account.all');
    Route::get('/payroll/pay_stub_account/{id}', [PayStubAccountController::class, 'getPayStubAccountById'])->name('payroll.pay_stub_account.getById');


    // Pay stub amendment
    Route::get('/payroll/pay_stub_amendment', [PayStubAmendmentController::class, 'index'])->name('payroll.pay_stub_amendment');
    Route::get('/payroll/pay_stub_amendment/form', [PayStubAmendmentController::class, 'form'])->name('payroll.pay_stub_amendment.form');
    Route::post('/payroll/pay_stub_amendment/create', [PayStubAmendmentController::class, 'createPayStubAmendment'])->name('payroll.pay_stub_amendment.create');
    Route::put('/payroll/pay_stub_amendment/update/{id}', [PayStubAmendmentController::class, 'updatePayStubAmendment'])->name('payroll.pay_stub_amendment.update');
    Route::delete('/payroll/pay_stub_amendment/delete/{id}', [PayStubAmendmentController::class, 'deletePayStubAmendment'])->name('payroll.pay_stub_amendment.delete');
    Route::get('/payroll/pay_stub_amendment/dropdown', [PayStubAmendmentController::class, 'getDropdownList'])->name('company.pay_stub_amendment.dropdown');
    Route::get('/payroll/pay_stub_amendment/allPayStubAmendment', [PayStubAmendmentController::class, 'getAllPayStubAmendment'])->name('payroll.pay_stub_amendment.all');
    Route::get('/payroll/pay_stub_amendment/{id}', [PayStubAmendmentController::class, 'getPayStubAmendmentById'])->name('payroll.pay_stub_amendment.getById');


    //   Pay Period Schedule
    Route::get('/payroll/pay_period_schedule', [PayPeriodScheduleController::class, 'index'])->name('payroll.pay_period_schedule');
    Route::get('/payroll/pay_period_schedule/form', [PayPeriodScheduleController::class, 'form'])->name('payroll.pay_period_schedule.form');
    Route::post('/payroll/pay_period_schedule/create', [PayPeriodScheduleController::class, 'createPayPeriodSchedule'])->name('payroll.pay_period_schedule.create');
    Route::put('/payroll/pay_period_schedule/update/{id}', [PayPeriodScheduleController::class, 'updatePayPeriodSchedule'])->name('payroll.pay_period_schedule.update');
    Route::delete('/payroll/pay_period_schedule/delete/{id}', [PayPeriodScheduleController::class, 'deletePayPeriodSchedule'])->name('payroll.pay_period_schedule.delete');
    Route::get('/payroll/pay_period_schedule/dropdown', [PayPeriodScheduleController::class, 'getPayPeriodScheduleDropdownData'])->name('company.pay_period_schedule.dropdown');
    Route::get('/payroll/pay_period_schedule/AllPayPeriodSchedules', [PayPeriodScheduleController::class, 'getAllPayPeriodSchedules'])->name('payroll.pay_period_schedule.all');
    Route::get('/payroll/pay_period_schedule/{id}', [PayPeriodScheduleController::class, 'getPayPeriodScheduleById'])->name('payroll.pay_period_schedule.getById');
    //   Pay Period Schedule
    Route::get('/payroll/company_deduction', [CompanyDeductionController::class, 'index'])->name('payroll.company_deduction');
    Route::get('/payroll/company_deduction/form', [CompanyDeductionController::class, 'form'])->name('payroll.company_deduction.form');
    Route::post('/payroll/company_deduction/create', [CompanyDeductionController::class, 'createCompanyDeduction'])->name('payroll.company_deduction.create');
    Route::put('/payroll/company_deduction/update/{id}', [CompanyDeductionController::class, 'updateCompanyDeduction'])->name('payroll.company_deduction.update');
    Route::delete('/payroll/company_deduction/delete/{id}', [CompanyDeductionController::class, 'deleteCompanyDeduction'])->name('payroll.company_deduction.delete');
    Route::get('/payroll/company_deduction/dropdown', [CompanyDeductionController::class, 'getCompanyDeductionDropdownData'])->name('company.company_deduction.dropdown');
    Route::get('/payroll/company_deduction/AllCompanyDeduction', [CompanyDeductionController::class, 'getAllCompanyDeduction'])->name('payroll.company_deduction.all');
    Route::get('/payroll/company_deduction/{id}', [CompanyDeductionController::class, 'getCompanyDeductionById'])->name('payroll.company_deduction.getById');

    //   Pay Pay Stub Entry Account Link
    Route::get('/payroll/pay_stub_entry_account_link', [PayStubEntryAccountLinkController::class, 'index'])->name('payroll.pay_stub_entry_account_link');
    Route::post('/payroll/pay_stub_entry_account_link/create', [PayStubEntryAccountLinkController::class, 'createPayStubEntryAccountLink'])->name('payroll.pay_stub_entry_account_link.create');
    Route::put('/payroll/pay_stub_entry_account_link/update/{id}', [PayStubEntryAccountLinkController::class, 'updatePayStubEntryAccountLink'])->name('payroll.pay_stub_entry_account_link.update');
    Route::get('/payroll/pay_stub_entry_account_link/dropdown', [PayStubEntryAccountLinkController::class, 'getPayStubEntryAccountLinkDropdownData'])->name('company.pay_stub_entry_account_link.dropdown');
    Route::get('/payroll/pay_stub_entry_account_link/{id}', [PayStubEntryAccountLinkController::class, 'getPayStubEntryAccountLinkById'])->name('payroll.pay_stub_entry_account_link.getById');

    //==============================================================================================================================
    // Employee Job History
    //==============================================================================================================================

    Route::get('/employee/jobhistory', [JobHistoryController::class, 'index'])->name('employee.jobhistory.index');
    Route::get('/employee/jobhistory/dropdown', [JobHistoryController::class, 'getJobHistoryDropdownData'])->name('employee.jobhistory.dropdown');
    Route::post('/employee/jobhistory/create', [JobHistoryController::class, 'createJobHistory'])->name('employee.jobhistory.create');
    Route::put('/employee/jobhistory/update/{id}', [JobHistoryController::class, 'updateJobHistory'])->name('employee.jobhistory.update');
    Route::delete('/employee/jobhistory/delete/{id}', [JobHistoryController::class, 'deleteJobHistory'])->name('employee.jobhistory.delete');
    Route::get('/employee/jobhistory/{id}', [JobHistoryController::class, 'getJobHistoryByEmployeeId'])->name('employee.jobhistory.getById');
    Route::get('/employee/single_jobhistory/{id}', [JobHistoryController::class, 'getJobHistoryBySingleEmployee'])->name('employee.jobhistory.single');


    //==============================================================================================================================
    // Employee wage
    //===============================================================================================================================

    Route::get('/employee/wage', [EmpWageController::class, 'index'])->name('employee_wage.index');
    Route::get('/employee/wage/dropdown', [EmpWageController::class, 'getWageDropDownList'])->name('employee_wage.dropdown');
    Route::post('/employee/wage/create', [EmpWageController::class, 'createEmployeeWage'])->name('employee_wage.create');
    Route::put('/employee/wage/update/{id}', [EmpWageController::class, 'updateEmployeeWage'])->name('employee_wage.update');
    Route::delete('/employee/wage/delete/{id}', [EmpWageController::class, 'deleteEmployeeWage'])->name('employee_wage.delete');
    Route::get('/employee/wage/{id}', [EmpWageController::class, 'getEmployeeWageById'])->name('employee_wage.getById');
    Route::get('/employee/single_wage/{id}', [EmpWageController::class, 'getSingleEmployeeWage'])->name('employee_wage.single');
    Route::get('/employee/wage/all', [EmpWageController::class, 'getAllEmployeeWage'])->name('employee_wage.all');


    //==============================================================================================================================
    // Employees
    //==============================================================================================================================
    Route::get('/employee/list', [EmployeeController::class, 'employee_list'])->name('employee.list');
    Route::get('/employee/form', [EmployeeController::class, 'employee_form'])->name('employee.form');
    Route::get('/emp/form', [EmployeeController::class, 'emp_form'])->name('emp.form');
    Route::get('/employee/profile', [EmployeeController::class, 'employee_profile'])->name('employee.profile');
    Route::get('/employee/dropdown', [EmployeeController::class, 'getEmployeeDropdownData'])->name('employee.dropdown');
    Route::get('/employee/next_employee_id', [EmployeeController::class, 'getNextEmployeeId'])->name('employee.nextEmployeeId');

    Route::get('/employee/bank/details/{id}', [EmployeeController::class, 'showBankDetails'])->name('employee.bank.details');
    Route::get('/employee/wage/details/{id}', [EmployeeController::class, 'showWageDetails'])->name('employee.wage.details');
    Route::get('/employee/qualification/details/{id}', [EmployeeController::class, 'showQualificationDetails'])->name('employee.qualification.details');
    Route::get('/employee/work_experience/details/{id}', [EmployeeController::class, 'showWorkExperianceDetails'])->name('employee.work_experience.details');
    Route::get('/employee/promotion/details/{id}', [EmployeeController::class, 'showPromotionDetails'])->name('employee.promotion.details');
    Route::get('/employee/family/details/{id}', [EmployeeController::class, 'showFamilyDetails'])->name('employee.family.details');
    Route::get('/employee/jobhistory/details/{id}', [EmployeeController::class, 'showJobHistoryDetails'])->name('employee.jobhistory.details');

    Route::post('/employee/create', [EmployeeController::class, 'createEmployee'])->name('employee.create');
    Route::get('/employee/update/{id}', [EmployeeController::class, 'updateEmployee'])->name('employee.update');
    Route::delete('/employee/delete/{id}', [EmployeeController::class, 'deleteEmployee'])->name('employee.delete');
    Route::get('/employees', [EmployeeController::class, 'getAllEmployees'])->name('employee.all');
    Route::get('/employee/{id}', [EmployeeController::class, 'getEmployeeByEmployeeId'])->name('employee.getById');

    //==============================================================================================================================
    // Policies
    //==============================================================================================================================

    // common functions
    Route::get('/policy/check_in_policy_groups/{policy}', [CommonPolicyController::class, 'checkInPolicyGroups']);


    // rounding policy
    Route::get('/policy/rounding', [RoundingPolicyController::class, 'index'])->name('policy.rounding');
    Route::get('/policy/rounding/dropdown', [RoundingPolicyController::class, 'getRoundingDropdownData'])->name('policy.rounding.dropdown');
    Route::get('/policy/roundings', [RoundingPolicyController::class, 'getAllRoundingPolicies'])->name('policy.roundings.all');
    Route::delete('/policy/rounding/delete/{id}', [RoundingPolicyController::class, 'deleteRoundingPolicy'])->name('policy.rounding.delete');
    Route::post('/policy/rounding/create', [RoundingPolicyController::class, 'createRoundingPolicy'])->name('policy.rounding.create');
    Route::put('/policy/rounding/update/{id}', [RoundingPolicyController::class, 'updateRoundingPolicy'])->name('policy.rounding.update');
    Route::get('/policy/rounding/{id}', [RoundingPolicyController::class, 'getRoundingPolicyById'])->name('policy.rounding.getById');

    // meal policy
    Route::get('/policy/meal', [MealPolicyController::class, 'index'])->name('policy.meal');
    Route::get('/policy/meal/dropdown', [MealPolicyController::class, 'getMealDropdownData'])->name('policy.meal.dropdown');
    Route::get('/policy/meals', [MealPolicyController::class, 'getAllMealPolicies'])->name('policy.meals.all');
    Route::delete('/policy/meal/delete/{id}', [MealPolicyController::class, 'deleteMealPolicy'])->name('policy.meal.delete');
    Route::post('/policy/meal/create', [MealPolicyController::class, 'createMealPolicy'])->name('policy.meal.create');
    Route::put('/policy/meal/update/{id}', [MealPolicyController::class, 'updateMealPolicy'])->name('policy.meal.update');
    Route::get('/policy/meal/{id}', [MealPolicyController::class, 'getMealPolicyById'])->name('policy.meal.getById');

    // break policy
    Route::get('/policy/break', [BreakPolicyController::class, 'index'])->name('policy.break');
    Route::get('/policy/break/dropdown', [BreakPolicyController::class, 'getBreakDropdownData'])->name('policy.break.dropdown');
    Route::get('/policy/breaks', [BreakPolicyController::class, 'getAllBreakPolicies'])->name('policy.breaks.all');
    Route::delete('/policy/break/delete/{id}', [BreakPolicyController::class, 'deleteBreakPolicy'])->name('policy.break.delete');
    Route::post('/policy/break/create', [BreakPolicyController::class, 'createBreakPolicy'])->name('policy.break.create');
    Route::put('/policy/break/update/{id}', [BreakPolicyController::class, 'updateBreakPolicy'])->name('policy.break.update');
    Route::get('/policy/break/{id}', [BreakPolicyController::class, 'getBreakPolicyById'])->name('policy.break.getById');

    // accrual policy
    Route::get('/policy/accrual', [AccrualPolicyController::class, 'index'])->name('policy.accrual');
    Route::get('/policy/accrual/form', [AccrualPolicyController::class, 'form'])->name('policy.accrual.form');
    Route::get('/policy/accrual/dropdown', [AccrualPolicyController::class, 'getAccrualDropdownData'])->name('policy.accrual.dropdown');
    Route::get('/policy/accruals', [AccrualPolicyController::class, 'getAllAccrualPolicies'])->name('policy.accruals.all');
    Route::delete('/policy/accrual/delete/{id}', [AccrualPolicyController::class, 'deleteAccrualPolicy'])->name('policy.accrual.delete');
    Route::post('/policy/accrual/create', [AccrualPolicyController::class, 'createAccrualPolicy'])->name('policy.accrual.create');
    Route::put('/policy/accrual/update/{id}', [AccrualPolicyController::class, 'updateAccrualPolicy'])->name('policy.accrual.update');
    Route::get('/policy/accrual/{id}', [AccrualPolicyController::class, 'getAccrualPolicyById'])->name('policy.accrual.getById');

    // exception policy
    Route::get('/policy/exception', [ExceptionPolicyController::class, 'index'])->name('policy.exception');
    Route::get('/policy/exception/form', [ExceptionPolicyController::class, 'form'])->name('policy.exception.form');
    Route::get('/policy/exceptions', [ExceptionPolicyController::class, 'getAllExceptionPolicies'])->name('policy.exceptions.all');
    Route::delete('/policy/exception/delete/{id}', [ExceptionPolicyController::class, 'deleteExceptionPolicy'])->name('policy.exception.delete');
    Route::post('/policy/exception/create', [ExceptionPolicyController::class, 'createExceptionPolicy'])->name('policy.exception.create');
    Route::put('/policy/exception/update/{id}', [ExceptionPolicyController::class, 'updateExceptionPolicy'])->name('policy.exception.update');
    Route::get('/policy/exception/{id}', [ExceptionPolicyController::class, 'getExceptionPolicyById'])->name('policy.exception.getById');


    // overtime policy
    Route::get('/policy/overtime', [OvertimePolicyController::class, 'index'])->name('policy.overtime');
    Route::get('/policy/overtime/dropdown', [OvertimePolicyController::class, 'getOvertimeDropdownData'])->name('policy.overtime.dropdown');
    Route::get('/policy/overtimes', [OvertimePolicyController::class, 'getAllOvertimePolicies'])->name('policy.overtimes.all');
    Route::delete('/policy/overtime/delete/{id}', [OvertimePolicyController::class, 'deleteOvertimePolicy'])->name('policy.overtime.delete');
    Route::post('/policy/overtime/create', [OvertimePolicyController::class, 'createOvertimePolicy'])->name('policy.overtime.create');
    Route::put('/policy/overtime/update/{id}', [OvertimePolicyController::class, 'updateOvertimePolicy'])->name('policy.overtime.update');
    Route::get('/policy/overtime/{id}', [OvertimePolicyController::class, 'getOvertimePolicyById'])->name('policy.overtime.getById');

    // premium policy
    Route::get('/policy/premium', [PremiumPolicyController::class, 'index'])->name('policy.premium');
    Route::get('/policy/premium/form', [PremiumPolicyController::class, 'form'])->name('policy.premium.form');
    Route::get('/policy/premium/dropdown', [PremiumPolicyController::class, 'getPremiumDropdownData'])->name('policy.premium.dropdown');
    Route::get('/policy/premiums', [PremiumPolicyController::class, 'getAllPremiumPolicies'])->name('policy.premiums.all');
    Route::delete('/policy/premium/delete/{id}', [PremiumPolicyController::class, 'deletePremiumPolicy'])->name('policy.premium.delete');
    Route::post('/policy/premium/create', [PremiumPolicyController::class, 'createPremiumPolicy'])->name('policy.premium.create');
    Route::put('/policy/premium/update/{id}', [PremiumPolicyController::class, 'updatePremiumPolicy'])->name('policy.premium.update');
    Route::get('/policy/premium/{id}', [PremiumPolicyController::class, 'getPremiumPolicyById'])->name('policy.premium.getById');

    // holiday policy
    Route::get('/policy/holiday', [HolidayPolicyController::class, 'index'])->name('policy.holiday');
    Route::get('/policy/holiday/form', [HolidayPolicyController::class, 'form'])->name('policy.holiday.form');
    Route::get('/policy/holiday/dropdown', [HolidayPolicyController::class, 'getHolidayDropdownData'])->name('policy.holiday.dropdown');
    Route::get('/policy/holidays', [HolidayPolicyController::class, 'getAllHolidayPolicies'])->name('policy.holidays.all');
    Route::delete('/policy/holiday/delete/{id}', [HolidayPolicyController::class, 'deleteHolidayPolicy'])->name('policy.holiday.delete');
    Route::post('/policy/holiday/create', [HolidayPolicyController::class, 'createHolidayPolicy'])->name('policy.holiday.create');
    Route::put('/policy/holiday/update/{id}', [HolidayPolicyController::class, 'updateHolidayPolicy'])->name('policy.holiday.update');
    Route::get('/policy/holiday/{id}', [HolidayPolicyController::class, 'getHolidayPolicyById'])->name('policy.holiday.getById');

    // absence policy
    Route::get('/policy/absence', [AbsencePolicyController::class, 'index'])->name('policy.absence');
    Route::get('/policy/absence/dropdown', [AbsencePolicyController::class, 'getAbsenceDropdownData'])->name('policy.absence.dropdown');
    Route::get('/policy/absences', [AbsencePolicyController::class, 'getAllAbsencePolicies'])->name('policy.absences.all');
    Route::delete('/policy/absence/delete/{id}', [AbsencePolicyController::class, 'deleteAbsencePolicy'])->name('policy.absence.delete');
    Route::post('/policy/absence/create', [AbsencePolicyController::class, 'createAbsencePolicy'])->name('policy.absence.create');
    Route::put('/policy/absence/update/{id}', [AbsencePolicyController::class, 'updateAbsencePolicy'])->name('policy.absence.update');
    Route::get('/policy/absence/{id}', [AbsencePolicyController::class, 'getAbsencePolicyById'])->name('policy.absence.getById');

    // schedule policy
    Route::get('/policy/schedule', [SchedulePolicyController::class, 'index'])->name('policy.schedule');
    Route::get('/policy/schedule/dropdown', [SchedulePolicyController::class, 'getScheduleDropdownData'])->name('policy.schedule.dropdown');
    Route::get('/policy/schedules', [SchedulePolicyController::class, 'getAllSchedulePolicies'])->name('policy.schedules.all');
    Route::delete('/policy/schedule/delete/{id}', [SchedulePolicyController::class, 'deleteSchedulePolicy'])->name('policy.schedule.delete');
    Route::post('/policy/schedule/create', [SchedulePolicyController::class, 'createSchedulePolicy'])->name('policy.schedule.create');
    Route::put('/policy/schedule/update/{id}', [SchedulePolicyController::class, 'updateSchedulePolicy'])->name('policy.schedule.update');
    Route::get('/policy/schedule/{id}', [SchedulePolicyController::class, 'getSchedulePolicyById'])->name('location.schedule.getById');

    // policy groups
    Route::get('/policy/policy_group', [PolicyGroupsController::class, 'index'])->name('policy.policy_group');
    Route::get('/policy/policy_group/form', [PolicyGroupsController::class, 'form'])->name('policy.policy_group.form');
    Route::get('/policy/policy_group/dropdown', [PolicyGroupsController::class, 'getPolicyGroupDropdownData'])->name('policy.policy_group.dropdown');
    Route::get('/policy/policy_groups', [PolicyGroupsController::class, 'getAllPolicyGroups'])->name('policy.policy_groups.all');
    Route::delete('/policy/policy_group/delete/{id}', [PolicyGroupsController::class, 'deletePolicyGroup'])->name('policy.policy_group.delete');
    Route::post('/policy/policy_group/create', [PolicyGroupsController::class, 'createPolicyGroup'])->name('policy.policy_group.create');
    Route::put('/policy/policy_group/update/{id}', [PolicyGroupsController::class, 'updatePolicyGroup'])->name('policy.policy_group.update');
    Route::get('/policy/policy_group/{id}', [PolicyGroupsController::class, 'getPolicyGroupById'])->name('location.policy_group.getById');


    // ========================================================================================================
    // ============= Reports ==============================
    // ========================================================================================================

     // Employee Detail Report
     Route::get('/reports/employee_detail_report', [EmployeeDetailReportController::class, 'index'])->name('reports.employee_detail_report');
     Route::get('/reports/employee_detail_report/report', [EmployeeDetailReportController::class, 'form'])->name('reports.employee_detail_report.report');
     Route::get('/reports/employee_detail_report/dropdown', [EmployeeDetailReportController::class, 'getDropdownData'])->name('reports.employee_detail_report.dropdown');
     Route::get('/reports/employee_detail_report/list', [EmployeeDetailReportController::class, 'getReportData'])->name('reports.employee_detail_report.list');

    //==============================================================================================================================
    // Company => this should be on the bottom of the page
    //==============================================================================================================================
    // Company Info index
    Route::get('/company/info', [CompanyController::class, 'index'])->name('company.info');
    Route::put('/company/update/{id}', [CompanyController::class, 'updateCompany'])->name('company.update');
    Route::get('/company/{id}', [CompanyController::class, 'getCompanyByCompanyId'])->name('company.getById');

    Route::get('/error', function () {
        return view('layouts.error');
    })->name('error');
});

require __DIR__ . '/auth.php';
