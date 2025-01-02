<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommonModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            ['employee_date.date_stamp', '>=', '"'.$start_date.'"'],
            ['employee_date.date_stamp', '<=', '"'.$end_date.'"'],
            //['employee_date_total.status', '=', $status],
            //['employee_date_total.type', '=', $type],
            ['employee_date.status', '=', '"active"'],
        ];
        $orderBy = 'employee_date.date_stamp asc, overtime_policy.type_id desc, employee_date_total.total_time asc';
        $res = $this->common->commonGetAll($table, $fields , $joinArr, $whereArr, $exceptDel = true, $connections = [], $groupBy = null, $orderBy);

        return $res;
    }

    public function getByEmployeeDateUserIdAndDate( $user_id, $date){
        if ( $user_id == '' ) {
			return FALSE;
		}

		if ( $date == '' OR $date <= 0 ) {
			return FALSE;
		}	
        
        $table = 'employee_date';
        $fields = ['*'];
        $joinArr = [];

        $whereArr = [
            ['employee_date.date_stamp', '<=', '"' . date('Y-m-d', strtotime($date)) . '"'],
            ['employee_date.employee_id', '=', $user_id],
        ];
        $orderBy = 'id ASC';

        // Fetch the pay period data
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true, [], null, $orderBy);

        return $res;
    }

    public function getWorkedTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id ){
        if ( $user_id == '' ) {
			return FALSE;
		}

		if ( $pay_period_id == '' ) {
			return FALSE;
		}

        $table = 'employee_date_total';
        $fields = [DB::raw('IFNULL(SUM(total_time), 0) as total_time')];
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'employee_date_total.employee_date_id'],
        ];

        $whereArr = [
            ['employee_date.employee_id', '=', $user_id],
            ['employee_date.pay_period_id', '=', $pay_period_id],
            '(employee_date_total.status = "worked" OR ( employee_date_total.status = "system" AND employee_date_total.type in ( "lunch", "break" ) ))',
            ['employee_date.status', '=', '"active"'],
        ];

        // Fetch the pay period data
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true);
        
        return $res;
    }

    public function getPaidAbsenceTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id ){
        if ( $user_id == '' ) {
			return FALSE;
		}

		if ( $pay_period_id == '' ) {
			return FALSE;
		}

        $table = 'employee_date_total';
        $fields = [DB::raw('IFNULL(SUM(total_time), 0) as total_time')];
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'employee_date_total.employee_date_id'],
            'absence_policy' => ['absence_policy.id', '=', 'employee_date_total.absence_policy_id'],
        ];

        $whereArr = [
            ['employee_date.employee_id', '=', $user_id],
            ['employee_date.pay_period_id', '=', $pay_period_id],
            ['employee_date_total.status', '=', '"system"'],
            'absence_policy.type in ( "paid", "paid_above_salary" )',
            ['employee_date_total.status', '=', '"absence"'],
            ['employee_date.status', '=', '"active"'],
        ];

        // Fetch the pay period data
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true);

        return $res;
    }

    public function getDockAbsenceTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id ){
        if ( $user_id == '' ) {
			return FALSE;
		}

		if ( $pay_period_id == '' ) {
			return FALSE;
		}

        $table = 'employee_date_total';
        $fields = [DB::raw('IFNULL(SUM(total_time), 0) as total_time')];
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'employee_date_total.employee_date_id'],
            'absence_policy' => ['absence_policy.id', '=', 'employee_date_total.absence_policy_id'],
        ];

        $whereArr = [
            ['employee_date.employee_id', '=', $user_id],
            ['employee_date.pay_period_id', '=', $pay_period_id],
            ['employee_date_total.status', '=', '"system"'],
            'absence_policy.type in ( "paid", "paid_above_salary" )',
            ['employee_date_total.status', '=', '"absence"'],
            ['absence_policy.type', '=', '"dock"'],
            ['employee_date.status', '=', '"active"'],
        ];

        // Fetch the pay period data
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true);

        return $res;
    }

    public function getRegularAndOverTimeSumByUserIDAndPayPeriodId( $user_id, $pay_period_id ){
        if ( $user_id == '' ) {
			return FALSE;
		}

		if ( $pay_period_id == '' ) {
			return FALSE;
		}

        $table = 'employee_date_total';
        $fields = [
            'employee_date_total.type as type', 
            'employee_date_total.over_time_policy_id as over_time_policy_id', 
            DB::raw('IFNULL(SUM(total_time), 0) as total_time')
        ];
        $joinArr = [
            'employee_date' => ['employee_date.id', '=', 'employee_date_total.employee_date_id'],
        ];

        $whereArr = [
            ['employee_date.employee_id', '=', $user_id],
            ['employee_date.pay_period_id', '=', $pay_period_id],
            ['employee_date_total.status', '=', '"system"'],
            ['employee_date.status', '=', '"active"'],
        ];

        $groupBy = ['type', 'over_time_policy_id'];

        $orderBy = 'type, over_time_policy_id';

        // Fetch the pay period data
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true, [], $groupBy, $orderBy);
        
        return $res;
    }

}