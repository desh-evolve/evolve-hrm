<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModel;
use Carbon\Carbon;

class CommonDateController extends Controller
{
    private $common;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    //get exception list
    public function getExceptionsByCompanyIDAndUserIdAndStartDateAndEndDate($company_id, $user_id, $start_date, $end_date) {
        // Input validations
        if ($company_id == '') {
            return FALSE;
        }
    
        if ($user_id == '') {
            return FALSE;
        }
    
        if ($start_date == '') {
            return FALSE;
        }
    
        if ($end_date == '') {
            return FALSE;
        }
    
        // Define table and fields
        $table = 'exception';
        $fields = ['exception.*', 'employee_date.date_stamp as user_date_stamp', 'exception_policy.severity as severity', 'exception_policy.type_id as exception_policy_type_id'];
        
        // Define table joins
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'exception.user_date_id'],
            'emp_employees' => ['emp_employees.id', '=', 'employee_date.employee_id'],
            'exception_policy' => ['exception_policy.id', '=', 'exception.exception_policy_id']
        ];
    
        // Define where conditions
        $whereArr = [
            ['emp_employees.company_id', '=', $company_id],
            ['employee_date.user_id', '=', $user_id],
            ['employee_date.date_stamp', '>=', '"' . date('Y-m-d', strtotime($start_date)) . '"'],
            ['employee_date.date_stamp', '<=', '"' . date('Y-m-d', strtotime($end_date)) . '"'],
            ['employee_date.status', '=', '"active"'],
        ];
    
        // Define ordering logic
        $orderBy = 'employee_date.date_stamp asc, exception_policy.type_id desc';
    
        // Fetch results
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel = false, $connections = [], $groupBy = null, $orderBy);
    
        return $res;
    }

    //get requests 
    public function getRequestsByCompanyIDAndUserIdAndStatusAndStartDateAndEndDate($company_id, $user_id, $status, $start_date, $end_date){
        if ( $company_id == '') {
			return FALSE;
		}

		if ( $user_id == '') {
			return FALSE;
		}

		if ( $status == '') {
			return FALSE;
		}

		if ( $start_date == '' ) {
			return FALSE;
		}

		if ( $end_date == '' ) {
			return FALSE;
		}

		if ( $order == NULL ) {
			//$order = array( 'type_id' => 'asc' );
			$strict = FALSE;
		} else {
			$strict = TRUE;
		}

        
        // Define table and fields
        $table = 'request';
        $fields = ['request.*', 'employee_date.date_stamp as user_date_stamp'];
        
        // Define table joins
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'request.employee_date_id'],
            'emp_employees' => ['emp_employees.id', '=', 'employee_date.employee_id'],
        ];
    
        // Define where conditions
        $whereArr = [
            ['emp_employees.company_id', '=', $company_id],
            ['employee_date.user_id', '=', $user_id],
            ['request.status', '=', $status],
            ['employee_date.date_stamp', '>=', '"' . date('Y-m-d', strtotime($start_date)) . '"'],
            ['employee_date.date_stamp', '<=', '"' . date('Y-m-d', strtotime($end_date)) . '"'],
            ['employee_date.status', '=', '"active"'],
        ];
    
        // Fetch results
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel = true);
    
        return $res;
    }
    

}