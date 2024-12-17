<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Termwind\Components\Dd;

class EmployeeDateController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getByCompanyIDAndUserIdAndStatusAndTypeAndStartDateAndEndDate($company_id, $employee_id, $status, $type, $start_date, $end_date){
        if ( $company_id == '' ) {
			return FALSE;
		}

		if ( $employee_id == '' ) {
			return FALSE;
		}

		if ( $start_date == '' ) {
			return FALSE;
		}

		if ( $end_date == '' ) {
			return FALSE;
		}

		if ( $status == '' ) {
			return FALSE;
		}

		if ( $type == '' ) {
			return FALSE;
		}

        $table = 'employee_date_total';
        $fields = ['employee_date_total.*', 'employee_date.date_stamp as user_date_stamp'];
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'employee_date_total.employee_date_id'],
            'emp_employees' => ['emp_employees.id', '=', 'employee_date.employee_id'],
            'overtime_policy' => ['overtime_policy.id', '=', 'employee_date_total.over_time_policy_id']
        ];
        $whereArr = [
            ['emp_employees.company_id', '=', $company_id],
            ['employee_date.employee_id', '=', $employee_id],
            ['employee_date.date_stamp', '>=', '"'.date('Y-m-d', $start_date).'"'],
            ['employee_date.date_stamp', '<=', '"'.date('Y-m-d', $end_date).'"'],
            //['employee_date_total.punch_status', '=', $status],
            //['employee_date_total.punch_type', '=', $type],
            ['employee_date.status', '=', '"active"'],
        ];
        $orderBy = 'employee_date.date_stamp asc, employee_date_total.punch_status asc, employee_date_total.punch_type asc, overtime_policy.type_id desc, employee_date_total.total_time asc';
        $res = $this->common->commonGetAll($table, $fields , $joinArr, $whereArr, $exceptDel = false, $connections = [], $groupBy = null, $orderBy);

        return $res;
    }

    
}