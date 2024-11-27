<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeQualificationController;
use App\Http\Controllers\Employee\EmployeeBankDetailsController;
use App\Http\Controllers\Employee\JobHistoryController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeWorkExperienceController;
use App\Http\Controllers\Employee\EmployeePromotionController;
use App\Http\Controllers\Employee\EmployeeFamilyController;
use App\Http\Controllers\Employee\EmpWageController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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

    // WageGroups routes
    Route::post('/company/wagegroups/create', [WageGroupController::class, 'createWageGroups'])->name('company.wagegroups.create');
    Route::put('/company/wagegroups/update/{id}', [WageGroupController::class, 'updateWageGroups'])->name('company.wagegroups.update');
    Route::delete('/company/wagegroups/delete/{id}', [WageGroupController::class, 'deleteWageGroups'])->name('company.wagegroups.delete');
    Route::get('/company/allwagegroups', [WageGroupController::class, 'getAllWageGroups'])->name('company.wagegroups.all');
    Route::get('/company/wagegroups/{id}', [WageGroupController::class, 'getWageGroupById'])->name('company.wagegroups.getById');



    //==============================================================================================================================
    // Employee Bank Details
    //==============================================================================================================================
    Route::get('/employee/bank', [EmployeeBankDetailsController::class, 'index'])->name('employee.bank.index');

    Route::get('/employee/bank/details/{id}', [EmployeeBankDetailsController::class, 'showBankDetails'])->name('employee.bank.details');
    Route::post('/employee/bank/create', [EmployeeBankDetailsController::class, 'createBankDetails'])->name('employee.bank.create');
    Route::put('/employee/bank/update/{id}', [EmployeeBankDetailsController::class, 'updateBankDetails'])->name('employee.bank.update');
    Route::delete('/employee/bank/delete/{id}', [EmployeeBankDetailsController::class, 'deleteBankDetails'])->name('employee.bank.delete');
    Route::get('/employee/bank/{id}', [EmployeeBankDetailsController::class, 'getBankDetailsByEmpId'])->name('employee.bank.getById');
    Route::get('/company/allemplyee', [EmployeeBankDetailsController::class, 'getAllEmployee'])->name('company.employee.all');


    //==============================================================================================================================
    // Currencies
    //==============================================================================================================================
    Route::get('/company/currency', [CurrencyController::class, 'index'])->name('company.currency.index');

    // Currency routes
    Route::post('/company/currency/create', [CurrencyController::class, 'createCurrency'])->name('company.currency.create');
    Route::put('/company/currency/update/{id}', [CurrencyController::class, 'updateCurrency'])->name('company.currency.update');
    Route::delete('/company/currency/delete/{id}', [CurrencyController::class, 'deleteCurrency'])->name('company.currency.delete');
    Route::get('/company/allcurrency', [CurrencyController::class, 'getAllCurrency'])->name('company.currency.all');
    Route::get('/company/currency/{id}', [CurrencyController::class, 'getCurrencyById'])->name('company.currency.getById');


    //==============================================================================================================================
    // Employee Qualification
    //==============================================================================================================================
    Route::get('/company/employee_qualification/index', [EmployeeQualificationController::class, 'index'])->name('company.employee_qualification.index');
    // Route::get('/company/employee_qualification', [EmployeeQualificationController::class, 'index'])->name('company.employee_qualification');

    // Employee Qualification routes
    Route::post('/company/employee_qualification/create', [EmployeeQualificationController::class, 'createEmployeeQualification'])->name('company.employee_qualification.create');
    Route::put('/company/employee_qualification/update/{id}', [EmployeeQualificationController::class, 'updateEmployeeQualification'])->name('company.employee_qualification.update');
    Route::delete('/company/employee_qualification/delete/{id}', [EmployeeQualificationController::class, 'deleteEmployeeQualification'])->name('company.employee_qualification.delete');
    Route::get('/company/single_employee_qualification/{id}', [EmployeeQualificationController::class, 'getSingleEmployeeQualification'])->name('company.employee_qualification.single');
    Route::get('/company/employee_qualification/dropdown', [EmployeeQualificationController::class, 'getEmployeeList'])->name('company.employee_qualification.dropdown');
    Route::get('/company/employee_qualification/{id}', [EmployeeQualificationController::class, 'getEmployeeQualificationById'])->name('company.employee_qualification.getById');

    // Employee employee_work_experience  routes
    Route::get('/company/employee_work_experience/index', [EmployeeWorkExperienceController::class, 'index'])->name('company.employee_work_experience.index');
    // Route::get('/company/employee_qualification', [EmployeeQualificationController::class, 'index'])->name('company.employee_qualification');

    Route::post('/company/employee_work_experience/create', [EmployeeWorkExperienceController::class, 'createEmployeeWorkExperience'])->name('company.employee_work_experience.create');
    Route::put('/company/employee_work_experience/update/{id}', [EmployeeWorkExperienceController::class, 'updateEmployeeWorkExperience'])->name('company.employee_work_experience.update');
    Route::delete('/company/employee_work_experience/delete/{id}', [EmployeeWorkExperienceController::class, 'deleteEmployeeWorkExperience'])->name('company.employee_work_experience.delete');
    Route::get('/company/single_employee_work_experience/{id}', [EmployeeWorkExperienceController::class, 'getSingleEmployeeWorkExperience'])->name('company.employee_work_experience.single');
    Route::get('/company/employee_work_experience/dropdown', [EmployeeWorkExperienceController::class, 'getEmployeeList'])->name('company.employee_work_experience.dropdown');
    Route::get('/company/employee_work_experience/{id}', [EmployeeWorkExperienceController::class, 'getEmployeeWorkExperienceById'])->name('company.employee_work_experience.getById');

    // Employee employee_promotion  routes
    Route::get('/company/employee_promotion/index', [EmployeePromotionController::class, 'index'])->name('company.employee_promotion.index');

    Route::post('/company/employee_promotion/create', [EmployeePromotionController::class, 'createEmployeePromotion'])->name('company.employee_promotion.create');
    Route::put('/company/employee_promotion/update/{id}', [EmployeePromotionController::class, 'updateEmployeePromotion'])->name('company.employee_promotion.update');
    Route::delete('/company/employee_promotion/delete/{id}', [EmployeePromotionController::class, 'deleteEmployeePromotion'])->name('company.employee_promotion.delete');
    Route::get('/company/single_employee_promotion/{id}', [EmployeePromotionController::class, 'getSingleEmployeePromotion'])->name('company.employee_promotion.single');
    Route::get('/company/employee_promotion/dropdown', [EmployeePromotionController::class, 'getEmployeeList'])->name('company.employee_promotion.dropdown');
    Route::get('/company/employee_promotion/{id}', [EmployeePromotionController::class, 'getEmployeePromotioneById'])->name('company.employee_promotion.getById');


    // Employee employee_promotion  routes
    Route::get('/company/employee_family/index', [EmployeeFamilyController::class, 'index'])->name('company.employee_family.index');

    Route::post('/company/employee_family/create', [EmployeeFamilyController::class, 'createEmployeeFamily'])->name('company.employee_family.create');
    Route::put('/company/employee_family/update/{id}', [EmployeeFamilyController::class, 'updateEmployeeFamily'])->name('company.employee_family.update');
    Route::delete('/company/employee_family/delete/{id}', [EmployeeFamilyController::class, 'deleteEmployeeFamily'])->name('company.employee_family.delete');
    Route::get('/company/single_employee_family/{id}', [EmployeeFamilyController::class, 'getSingleEmployeeFamily'])->name('company.employee_family.single');
    Route::get('/company/employee_family/dropdown', [EmployeeFamilyController::class, 'getEmployeeList'])->name('company.employee_family.dropdown');
    Route::get('/company/employee_family/{id}', [EmployeeFamilyController::class, 'getEmployeeFamilyById'])->name('company.employee_family.getById');


    Route::get('/company/employee_punch/index', [PunchController::class, 'index'])->name('company.employee_punch.index');
    //  Route::get('/company/employee_punch/{id}', [PunchController::class, 'getEmployeePunchById'])->name('company.employee_punch.getById');
    Route::get('/company/employee_punch/dropdown', [PunchController::class, 'getDropdownData'])->name('company.employee_punch.dropdown');
    Route::post('/company/employee_punch/create', [PunchController::class, 'createEmployeePunch'])->name('company.employee_punch.create');
    Route::get('/company/employee_punch/{id}', [PunchController::class, 'getEmployeePunchById'])->name('company.employee_punch.getById');
    Route::get('/company/single_employee_punch/{id}', [PunchController::class, 'getSingleEmployeePunch'])->name('company.employee_punch.single');
    Route::put('/company/employee_punch/update/{id}', [PunchController::class, 'updateEmployeePunch'])->name('company.employee_punch.update');

    // ===========mass-punch=================
    Route::get('/company/mass_punch/index', [MassPunchController::class, 'index'])->name('company.mass_punch.index');
    Route::get('/company/mass_punch/dropdown', [MassPunchController::class, 'getDropdownData'])->name('company.mass_punch.dropdown');
    Route::post('/company/mass_punch/create', [MassPunchController::class, 'createMassPunch'])->name('company.mass_punch.create');




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

    Route::get('/employee-wage', [EmpWageController::class, 'index'])->name('employee_wage.index');


    //==============================================================================================================================
    // Employees
    //==============================================================================================================================
    Route::get('/employee/list', [EmployeeController::class, 'employee_list'])->name('employee.list');
    Route::get('/employee/form', [EmployeeController::class, 'employee_form'])->name('employee.form');
    Route::get('/employee/profile', [EmployeeController::class, 'employee_profile'])->name('employee.profile');
    Route::get('/employee/dropdown', [EmployeeController::class, 'getEmployeeDropdownData'])->name('employee.dropdown');
    Route::get('/employee/next_employee_id', [EmployeeController::class, 'getNextEmployeeId'])->name('employee.nextEmployeeId');

    Route::post('/employee/create', [EmployeeController::class, 'createEmployee'])->name('employee.create');
    Route::get('/employee/update/{id}', [EmployeeController::class, 'updateEmployee'])->name('employee.update');
    Route::delete('/employee/delete/{id}', [EmployeeController::class, 'deleteEmployee'])->name('employee.delete');
    Route::get('/employees', [EmployeeController::class, 'getAllEmployees'])->name('employee.all');
    Route::get('/employee/{id}', [EmployeeController::class, 'getEmployeeByEmployeeId'])->name('employee.getById');

    //==============================================================================================================================
    // Policies
    //==============================================================================================================================

    // rounding policy

    Route::get('/policy/rounding', [RoundingPolicyController::class, 'index'])->name('policy.rounding');
    Route::get('/policy/rounding/dropdown', [RoundingPolicyController::class, 'getRoundingDropdownData'])->name('policy.rounding.dropdown');
    Route::get('/policy/roundings', [RoundingPolicyController::class, 'getAllRoundingPolicies'])->name('policy.roundings.all');
    Route::delete('/policy/rounding/delete/{id}', [RoundingPolicyController::class, 'deleteRoundingPolicy'])->name('policy.rounding.delete');
    Route::post('/policy/rounding/create', [RoundingPolicyController::class, 'createRoundingPolicy'])->name('policy.rounding.create');
    Route::put('/policy/rounding/update/{id}', [RoundingPolicyController::class, 'updateRoundingPolicy'])->name('policy.rounding.update');
    Route::get('/policy/rounding/{id}', [RoundingPolicyController::class, 'getRoundingPolicyById'])->name('location.rounding.getById');

    // meal policy
    Route::get('/policy/meal', [MealPolicyController::class, 'index'])->name('policy.meal');
    Route::get('/policy/meal/dropdown', [MealPolicyController::class, 'getMealDropdownData'])->name('policy.meal.dropdown');
    Route::get('/policy/meals', [MealPolicyController::class, 'getAllMealPolicies'])->name('policy.meals.all');
    Route::delete('/policy/meal/delete/{id}', [MealPolicyController::class, 'deleteMealPolicy'])->name('policy.meal.delete');

    // break policy
    Route::get('/policy/break', [BreakPolicyController::class, 'index'])->name('policy.break');
    Route::get('/policy/break/dropdown', [BreakPolicyController::class, 'getBreakDropdownData'])->name('policy.break.dropdown');
    Route::get('/policy/breaks', [BreakPolicyController::class, 'getAllBreakPolicies'])->name('policy.breaks.all');
    Route::delete('/policy/break/delete/{id}', [BreakPolicyController::class, 'deleteBreakPolicy'])->name('policy.break.delete');

    // accrual policy
    Route::get('/policy/accrual', [AccrualPolicyController::class, 'index'])->name('policy.accrual');
    Route::get('/policy/accrual/form', [AccrualPolicyController::class, 'form'])->name('policy.accrual.form');
    Route::get('/policy/accrual/dropdown', [AccrualPolicyController::class, 'getAccrualDropdownData'])->name('policy.accrual.dropdown');
    Route::get('/policy/accruals', [AccrualPolicyController::class, 'getAllAccrualPolicies'])->name('policy.accruals.all');
    Route::delete('/policy/accrual/delete/{id}', [AccrualPolicyController::class, 'deleteAccrualPolicy'])->name('policy.accrual.delete');

    // exception policy
    Route::get('/policy/exception', [ExceptionPolicyController::class, 'index'])->name('policy.exception');
    Route::get('/policy/exception/form', [ExceptionPolicyController::class, 'form'])->name('policy.exception.form');
    Route::get('/policy/exceptions', [ExceptionPolicyController::class, 'getAllExceptionPolicies'])->name('policy.exceptions.all');
    Route::delete('/policy/exception/delete/{id}', [ExceptionPolicyController::class, 'deleteExceptionPolicy'])->name('policy.exception.delete');

    // overtime policy
    Route::get('/policy/overtime', [OvertimePolicyController::class, 'index'])->name('policy.overtime');
    Route::get('/policy/overtime/dropdown', [OvertimePolicyController::class, 'getOvertimeDropdownData'])->name('policy.overtime.dropdown');
    Route::get('/policy/overtimes', [OvertimePolicyController::class, 'getAllOvertimePolicies'])->name('policy.overtimes.all');
    Route::delete('/policy/overtime/delete/{id}', [OvertimePolicyController::class, 'deleteOvertimePolicy'])->name('policy.overtime.delete');

    // premium policy
    Route::get('/policy/premium', [PremiumPolicyController::class, 'index'])->name('policy.premium');
    Route::get('/policy/premium/form', [PremiumPolicyController::class, 'form'])->name('policy.premium.form');
    Route::get('/policy/premium/dropdown', [PremiumPolicyController::class, 'getPremiumDropdownData'])->name('policy.premium.dropdown');
    Route::get('/policy/premiums', [PremiumPolicyController::class, 'getAllPremiumPolicies'])->name('policy.premiums.all');
    Route::delete('/policy/premium/delete/{id}', [PremiumPolicyController::class, 'deletePremiumPolicy'])->name('policy.premium.delete');

    // holiday policy
    Route::get('/policy/holiday', [HolidayPolicyController::class, 'index'])->name('policy.holiday');
    Route::get('/policy/holiday/form', [HolidayPolicyController::class, 'form'])->name('policy.holiday.form');
    Route::get('/policy/holiday/dropdown', [HolidayPolicyController::class, 'getHolidayDropdownData'])->name('policy.holiday.dropdown');
    Route::get('/policy/holidays', [HolidayPolicyController::class, 'getAllHolidayPolicies'])->name('policy.holidays.all');
    Route::delete('/policy/holiday/delete/{id}', [HolidayPolicyController::class, 'deleteHolidayPolicy'])->name('policy.holiday.delete');

    // absence policy
    Route::get('/policy/absence', [AbsencePolicyController::class, 'index'])->name('policy.absence');
    Route::get('/policy/absence/dropdown', [AbsencePolicyController::class, 'getAbsenceDropdownData'])->name('policy.absence.dropdown');
    Route::get('/policy/absences', [AbsencePolicyController::class, 'getAllAbsencePolicies'])->name('policy.absences.all');
    Route::delete('/policy/absence/delete/{id}', [AbsencePolicyController::class, 'deleteAbsencePolicy'])->name('policy.absence.delete');

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
