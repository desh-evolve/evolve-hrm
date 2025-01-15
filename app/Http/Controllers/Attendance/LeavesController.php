<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class LeavesController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:apply leaves', ['only' => ['form', '']]);

        $this->common = new CommonModel();
    }

    public function form()
    {
        $user_id = Auth::user()->id;

        $accrual_policies = $this->common->commonGetAll(
            'accrual_policy', 
            ['id', 'name', 'type']
        );

        $current_user_data = $this->common->commonGetById(
            $user_id, 
            'user_id', 
            'emp_employees', 
            '*', 
            [ 'com_employee_designations' => ['com_employee_designations.id', '=', 'emp_employees.designation_id']]
        );

        $emp_list = $this->common->commonGetAll(
            'emp_employees', 
            ['user_id', 'id AS emp_id', 'title', 'first_name', 'last_name'], 
            [], 
            ['user_id' => ['user_id', '!=', $user_id]]
        );
        
        $leave_types = $accrual_policies;

        $parse_obj = [
            'user_data' => $current_user_data[0],
            'leave_types' => $leave_types,
            'emp_list' => $emp_list
        ];

        return view('attendance.leaves.form', $parse_obj);
    }

}

?>