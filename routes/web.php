<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndustryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\EmployeeDesignationController;

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

Route::group(['middleware' => ['role:super-admin|admin']], function() {

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);

    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);



    // industry
    Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
    Route::post('/industries/create', [IndustryController::class, 'create'])->name('industries.create');
    Route::put('/industries/update/{id}', [IndustryController::class, 'update'])->name('industries.update');
    Route::get('/industries/edit', [IndustryController::class, 'edit'])->name('industries.edit');
    Route::delete('/industries/delete/{id}', [IndustryController::class, 'delete'])->name('industries.delete');
    Route::get('/industries/{id}', [IndustryController::class, 'show'])->name('industries.show');

    // company
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::put('/companies/update/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::get('/companies/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::delete('/companies/delete/{id}', [CompanyController::class, 'delete'])->name('companies.delete');
    Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');

    // country
    Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::post('/currencies/create', [CurrencyController::class, 'create'])->name('currencies.create');
    Route::put('/currencies/update/{id}', [CurrencyController::class, 'update'])->name('currencies.update');
    Route::get('/currencies/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
    Route::delete('/currencies/delete/{id}', [CurrencyController::class, 'delete'])->name('currencies.delete');
    Route::get('/currencies/{id}', [CurrencyController::class, 'show'])->name('currencies.show');

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

    // Branch routes
    Route::get('/company/branch', [BranchController::class, 'index'])->name('company.branch.index');
    Route::get('/company/branch/dropdown', [BranchController::class, 'getBranchDropdownData'])->name('company.branch.dropdown');
    Route::post('/company/branch/create', [BranchController::class, 'createBranch'])->name('company.branch.create');
    Route::put('/company/branch/update/{id}', [BranchController::class, 'updateBranch'])->name('company.branch.update');
    Route::delete('/company/branch/delete/{id}', [BranchController::class, 'deleteBranch'])->name('company.branch.delete');
    Route::get('/company/branches', [BranchController::class, 'getAllBranches'])->name('company.branches.all');
    Route::get('/company/branch/{id}', [BranchController::class, 'getBranchByBranchId'])->name('company.branch.getById');

    // Departments routes
    Route::get('/company/department', [DepartmentController::class, 'index'])->name('company.department.index');
    Route::get('/company/department/dropdown', [DepartmentController::class, 'getDepartmentDropdownData'])->name('company.department.dropdown');
    Route::post('/company/department/create', [DepartmentController::class, 'createDepartment'])->name('company.department.create');
    Route::put('/company/department/update/{id}', [DepartmentController::class, 'updateDepartment'])->name('company.department.update');
    Route::delete('/company/department/delete/{id}', [DepartmentController::class, 'deleteDepartment'])->name('company.department.delete');
    Route::get('/company/departments', [DepartmentController::class, 'getAllDepartments'])->name('company.departments.all');
    Route::get('/company/department/{id}', [DepartmentController::class, 'getDepartmentByDepartmentId'])->name('company.department.getById');
    Route::get('/company/departments/{branchId}', [DepartmentController::class, 'getDepartmentsByBranchId'])->name('company.departments.getByBranchId');

    //==============================================================================================================================

    // Station index
    Route::get('/station', [StationController::class, 'index'])->name('station.index');
    
    // Country routes
    Route::post('/station/create', [StationController::class, 'createStation'])->name('station.create');
    Route::put('/station/update/{id}', [StationController::class, 'updateStation'])->name('station.update');
    Route::delete('/station/delete/{id}', [StationController::class, 'deleteStation'])->name('station.delete');
    Route::get('/stations', [StationController::class, 'getAllStations'])->name('stations.all');
    Route::get('/station/{id}', [StationController::class, 'getStationByStationId'])->name('station.getById');
    

    // Divisions routes
    Route::post('/station/station_type/create', [StationController::class, 'createStationType'])->name('station.station_type.create');
    Route::put('/station/station_type/update/{id}', [StationController::class, 'updateStationType'])->name('station.station_type.update');
    Route::delete('/station/station_type/delete/{id}', [StationController::class, 'deleteStationType'])->name('station.station_type.delete');
    Route::get('/station/station_types', [StationController::class, 'getAllStationTypes'])->name('station.station_types.all');
    Route::get('/station/station_type/{id}', [StationController::class, 'getStationTypeByStationId'])->name('station.station_type.getById');
   
   
    // employee_designation routes
     // Station indeX
     Route::get('company/employee_designation', [EmployeeDesignationController::class, 'index'])->name('company.employee_designation');

    Route::post('/company/employee_designation/create', [EmployeeDesignationController::class, 'createEmployeeDesignation'])->name('company.employee_designation.create');
    Route::put('/company/employee_designation/update/{id}', [EmployeeDesignationController::class, 'updateEmployeeDesignation'])->name('company.employee_designation.update');
    Route::delete('/company/employee_designation/delete/{id}', [EmployeeDesignationController::class, 'deleteEmployeeDesignation'])->name('company.employee_designation.delete');
    Route::get('/company/employee_designations', [EmployeeDesignationController::class, 'getAllEmployeeDesignations'])->name('company.employee_designation.all');
    Route::get('/company/employee_designation/{id}', [EmployeeDesignationController::class, 'getEmployeeDesignationById'])->name('company.employee_designation.getById');
    
    //==============================================================================================================================
    // Company
    //==============================================================================================================================

    // Company Info index
    Route::get('/company/info', [CompanyController::class, 'index'])->name('company.info');

    Route::get('/currencies', function () {
        return view('company/currencies/currencies_add');
    })->name('currencies.index');


    //==============================================================================================================================
    // Wage Groups
    //==============================================================================================================================


    Route::get('/wagegroups', [WageGroupController::class, 'wageGroup'])->name('company.wagegroups');

    // WageGroups routes
    Route::post('/company/wagegroups/create', [WageGroupController::class, 'createWageGroups'])->name('company.wagegroups.create');
    Route::put('/company/wagegroups/update/{id}', [WageGroupController::class, 'updateWageGroups'])->name('company.wagegroups.update');
    Route::delete('/company/wagegroups/delete/{id}', [WageGroupController::class, 'deleteWageGroups'])->name('company.wagegroups.delete');
    Route::get('/company/allwagegroups', [WageGroupController::class, 'getAllWageGroups'])->name('company.wagegroups.all');
    Route::get('/company/wagegroups/{id}', [WageGroupController::class, 'getWageGroupById'])->name('company.wagegroups.getById');


});



require __DIR__.'/auth.php';
