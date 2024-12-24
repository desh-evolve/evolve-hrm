<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CommonModel;
use Carbon\Carbon;

class CommonController extends Controller
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
        $fields = [
            'exception.*', 
            'employee_date.date_stamp as user_date_stamp', 
            'exception_policy.severity as severity', 
            'exception_policy.type_id as exception_policy_type_id',
            DB::raw("
                CASE
                    WHEN severity = 'low' THEN '#000000' 
                    WHEN severity = 'medium' THEN '#0000FF'
                    WHEN severity = 'high' THEN '#FF9900'
                    WHEN severity = 'critical' THEN '#FF0000'
                    ELSE '#666666'
                END AS color
            ")
        ];

        /* '#666666' => 'gray', '#000000' => 'black', '#0000FF' => 'blue', '#FF9900' => 'blue', '#FF0000' => 'red' */

        // Define table joins
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'exception.user_date_id'],
            'emp_employees' => ['emp_employees.id', '=', 'employee_date.employee_id'],
            'exception_policy' => ['exception_policy.id', '=', 'exception.exception_policy_id']
        ];
    
        // Define where conditions
        $whereArr = [
            ['emp_employees.company_id', '=', $company_id],
            ['employee_date.employee_id', '=', $user_id],
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
            ['employee_date.employee_id', '=', $user_id],
            ['request.status', '=', $status],
            ['employee_date.date_stamp', '>=', '"' . date('Y-m-d', strtotime($start_date)) . '"'],
            ['employee_date.date_stamp', '<=', '"' . date('Y-m-d', strtotime($end_date)) . '"'],
            ['employee_date.status', '=', '"active"'],
        ];
    
        // Fetch results
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel = true);
    
        return $res;
    }

    // get holidays
    public function getHolidaysByPolicyGroupUserId($user_id, $start_date, $end_date) {
		if ( $user_id == '') {
			return FALSE;
		}

		if ( $start_date == '') {
			return FALSE;
		}

		if ( $end_date == '') {
			return FALSE;
		}

        
        // Define table and fields
        $table = 'holidays';
        $fields = ['holidays.*'];
        
        // Define table joins
        $joinArr = [
            'holiday_policy' => ['holiday_policy.id', '=', 'holidays.holiday_policy_id'],
            'policy_group' => ['policy_group.company_id', '=', 'holiday_policy.company_id'],
            'policy_group_employees' => ['policy_group_employees.policy_group_id', '=', 'policy_group.id'],
        ];
    
        // Define where conditions
        $whereArr = [
            ['policy_group_employees.employee_id', '=', $user_id],
            ['holidays.date_stamp', '>=', '"' . date('Y-m-d', strtotime($start_date)) . '"'],
            ['holidays.date_stamp', '<=', '"' . date('Y-m-d', strtotime($end_date)) . '"'],
            ['holiday_policy.status', '=', '"active"'],
            ['policy_group.status', '=', '"active"'],
        ];
    
        // Fetch results
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel = true);
    
        return $res;
	}
    
    //get pay period
    public function getPayPeriodByUserIdAndDate($user_id, $end_date){
        if ( $user_id == '' ) {
			return FALSE;
		}

		if ( $end_date == '' OR $end_date <= 0 ) {
			return FALSE;
		}

        $fields = ['*'];
        $joinArr = [
            'pay_period_schedule' => ['pay_period_schedule.id', '=', 'pay_period.pay_period_schedule_id'],
            'pay_period_schedule_employee' => ['pay_period_schedule_employee.pay_period_schedule_id', '=', 'pay_period_schedule.id']
        ];

        $whereArr = [
            ['DATE(pay_period.start_date)', '<=', '"' . date('Y-m-d', strtotime($end_date)) . '"'],
            ['DATE(pay_period.end_date)', '>=', '"' . date('Y-m-d', strtotime($end_date)) . '"'],
            ['pay_period_schedule_employee.employee_id', '=', $user_id],
            ['pay_period_schedule.status', '=', '"active"'],
        ];

        // Fetch the pay period data
        $res = $this->common->commonGetAll('pay_period', $fields, $joinArr, $whereArr, true);

        return $res;
    }

    public function getPayPeriodTimeSheetByPayPeriodIdAndUserId( $pay_period_id, $user_id ){
        if ( $pay_period_id == '') {
			return FALSE;
		}

		if ( $user_id == '') {
			return FALSE;
		}

        $table = 'pay_period_time_sheet_verify';
        $fields = ['*'];
        $joinArr = [];

        $whereArr = [
            ['pay_period_time_sheet_verify.user_id', '=', $user_id],
            ['pay_period_time_sheet_verify.pay_period_id', '=', $pay_period_id],
        ];

        // Fetch the pay period data
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true);
        
        return $res;
    }

    public function getOverTimePolicyOptions( $company_id ){

        $res = $this->common->commonGetById($company_id, 'company_id', 'overtime_policy', '*');

        $list = [];
        if(count($res) > 0){
            foreach($res as $re){
                $list[$re->id] = $re->name;
            }
        }

        return $list;
    }

}