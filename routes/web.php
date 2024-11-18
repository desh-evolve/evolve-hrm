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

// employee
// use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeQualificationController;
use App\Http\Controllers\Employee\JobHistoryController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeWorkExperienceController;
use App\Http\Controllers\Employee\EmployeePromotionController;
use App\Http\Controllers\Employee\EmployeeFamilyController;
use App\Http\Controllers\Employee\EmpWageController;

// policies
use App\Http\Controllers\Policy\RoundingPolicyController;
use App\Http\Controllers\Policy\MealPolicyController;
use App\Http\Controllers\Policy\ExceptionPolicyController;
use App\Http\Controllers\Policy\OvertimePolicyController;

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
 
    //==============================================================================================================================
    // Employee Wage
    //==============================================================================================================================

     // Employee employee_promotion  routes
     Route::get('/company/employee_wage/index', [EmpWageController::class, 'index'])->name('employee_wage.index');
    
     Route::post('/company/employee_wage/create', [EmpWageController::class, 'createEmployeeWage'])->name('company.employee_wage.create');
     Route::put('/company/employee_wage/update/{id}', [EmpWageController::class, 'updateEmployeeWage'])->name('company.employee_wage.update');
     Route::delete('/company/employee_wage/delete/{id}', [EmpWageController::class, 'deleteEmployeeWage'])->name('company.employee_wage.delete');
     Route::get('/company/single_employee_wage/{id}', [EmpWageController::class, 'getSingleEmployeeWage'])->name('company.employee_wage.single');
     Route::get('/company/employee_wage/dropdown', [EmpWageController::class, 'getDropDownList'])->name('company.employee_wage.dropdown');
     Route::get('/company/employee_wage/{id}', [EmpWageController::class, 'getEmployeeWageById'])->name('company.employee_wage.getById');
 
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
    Route::get('/policy/rounding/form', [RoundingPolicyController::class, 'form'])->name('policy.rounding.form');
    Route::get('/policy/rounding/dropdown', [RoundingPolicyController::class, 'getRoundingDropdownData'])->name('policy.rounding.dropdown');
    Route::get('/policy/roundings', [RoundingPolicyController::class, 'getAllRoundingPolicies'])->name('policy.roundings.all');
    Route::delete('/policy/rounding/delete/{id}', [RoundingPolicyController::class, 'deleteRoundingPolicy'])->name('policy.rounding.delete');

    // meal policy
    Route::get('/policy/meal', [MealPolicyController::class, 'index'])->name('policy.meal');
    Route::get('/policy/meal/form', [MealPolicyController::class, 'form'])->name('policy.meal.form');
    Route::get('/policy/meal/dropdown', [MealPolicyController::class, 'getMealDropdownData'])->name('policy.meal.dropdown');
    Route::get('/policy/meals', [MealPolicyController::class, 'getAllMealPolicies'])->name('policy.meals.all');
    Route::delete('/policy/meal/delete/{id}', [MealPolicyController::class, 'deleteMealPolicy'])->name('policy.meal.delete');

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
