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
        return view('employee.employee_list');
    }

    public function employee_form()
    {
        return view('employee.employee_form');
    }

    public function getAllEmployees(){}
    public function getEmployeeByEmployeeId(){}
    public function createEmployee(){}
    public function updateEmployee(){}
    public function deleteEmployee(){}

}
