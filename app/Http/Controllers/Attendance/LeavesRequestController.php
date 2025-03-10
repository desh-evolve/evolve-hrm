<?php

namespace App\Http\Controllers\Attendance;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class LeavesRequestController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:apply leaves', ['only' => ['', '']]);

        $this->common = new CommonModel();
    }

    public function getByUserIdAndCompanyId($user_id, $company_id){
        if ( $user_id == '') {
			return FALSE;
		}

		// if ( $company_id == '') {
		// 	return FALSE;
		// }

        $table = 'leave_request';
        $fields = [
            'leave_request.*',
            'emp_employees.first_name AS fname',
            'emp_employees.last_name AS lname',
            'emp_employees.id AS emp_id'
        ];
        $joinArr = [
            'emp_employees' => ['emp_employees.user_id', '=', 'leave_request.user_id']
        ];
        $res = $this->common->commonGetById($user_id, $table.'.user_id', $table, $fields, $joinArr, [], true);

        return $res;
    }

    public function getPayperiodsShortLeaveCount($user_id,$leave_policy, $pp_start_date,$pp_end_date){
        if ( $pp_end_date == '' ||  $pp_end_date == '' || $user_id == '') {
			return FALSE;
		}

        $table = 'leave_request';
        $fields = [DB::raw('count(*) as count')];
        $joinArr = [];
        $whereArr = [
            'user_id' => $user_id,
            'accurals_policy_id'=> $leave_policy,
        ];
        $row = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr);

        if ( $row['count'] === NULL ) {
                $row['count'] = 0;
        }

        return $row;
    }

    public function checkUserHasLeaveTypeForDay($user_id,$leave_date,$leave_policy){
        if ( $leave_date == '' || $user_id == '') {
			return FALSE;
		}
              
        $table = 'leave_request';
        $fields = '*';
        $joinArr = [];
        $whereArr = [
            'user_id' => $user_id,
            'accurals_policy_id' => $leave_policy,
            'is_covered_approved' => 1, 
            'is_supervisor_approved' => 0, 
            'is_hr_approved' => 0, 
            "'".$leave_date."' between leave_from and leave_to "
        ];
        $row = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr);

        if ( $row['count'] === NULL ) {
                $row['count'] = 0;
        }

        return $row;
    }
}
?>