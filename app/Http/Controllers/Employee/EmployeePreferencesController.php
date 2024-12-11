<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeePreferencesController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee promotion', ['only' => ['']]);
        $this->middleware('permission:create employee promotion', ['only' => ['']]);
        $this->middleware('permission:update employee promotion', ['only' => ['']]);
        $this->middleware('permission:delete employee promotion', ['only' => ['']]);

        $this->common = new CommonModel();
    }

    public function getEmployeePreferencesByEmployeeId($employee_id){
        $ep = $this->common->commonGetById($employee_id, 'employee_id', 'emp_preference', '*');
        return $ep;
    }

}