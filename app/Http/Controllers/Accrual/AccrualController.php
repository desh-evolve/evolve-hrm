<?php

namespace App\Http\Controllers\Accrual;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AccrualController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        //$this->middleware('permission:apply leaves', ['only' => ['', '']]);

        $this->common = new CommonModel();
    }

    public function getByCompanyIdAndUserIdAndAccrualPolicyIdAndStatusForLeave($company_id, $user_id, $accrual_policy_id,$type) {
		if ( $user_id == '' || $accrual_policy_id == '' || $type == '') {
			return FALSE;
		}

        $table = 'accrual';
        $fields = [DB::raw('sum(accrual.amount) as amount, user_date.date_stamp as date_stamp')];
        $joinArr = [
            'users' => ['users.id', '=', 'accrual.user_id'],
            'emp_employees' => ['emp_employees.user_id', '=', 'accrual.user_id'],
            'user_date_total' => ['user_date_total.id', '=', 'accrual.user_date_total_id'],
            'user_date' => ['user_date.id', '=', 'user_date_total.user_date_id'],
        ];

        $whereArr = [
            'accrual.user_id' => $user_id,
            //'users.company_id' => $company_id,
            'accrual.accrual_policy_id' => $accrual_policy_id,
            'accrual.type' => '"'.$type.'"', 
            'emp_employees.status' => '"active"',
            '( accrual.user_date_total_id IS NULL OR ( accrual.user_date_total_id IS NOT NULL AND user_date_total.status != "delete" AND user_date.status != "delete") )',
        ];

        $groupBy = 'user_date.date_stamp';
        $orderBy = 'user_date.date_stamp desc, accrual.time_stamp desc';

        $res = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, false, [], $groupBy, $orderBy );
        
        return $res;
	}

    public function getByCompanyIdAndUserIdAndAccrualPolicyIdAndStatus($company_id, $user_id, $accrual_policy_id, $type) {
        if ( $user_id == '' || $accrual_policy_id == '' || $type == '') {
			return FALSE;
		}

        $table = 'accrual';
        $fields = [DB::raw('accrual.*, user_date.date_stamp as date_stamp')];
        $joinArr = [
            'users' => ['users.id', '=', 'accrual.user_id'],
            'emp_employees' => ['emp_employees.user_id', '=', 'accrual.user_id'],
            'user_date_total' => ['user_date_total.id', '=', 'accrual.user_date_total_id'],
            'user_date' => ['user_date.id', '=', 'user_date_total.user_date_id'],
        ];

        $whereArr = [
            'accrual.user_id' => $user_id,
            //'users.company_id' => $company_id,
            'accrual.accrual_policy_id' => $accrual_policy_id,
            'accrual.type' => $type, 
            'emp_employees.status' => '"active"',
            '( accrual.user_date_total_id IS NULL OR ( accrual.user_date_total_id IS NOT NULL AND user_date_total.status != "delete" AND user_date.status != "delete") )',
        ];

        $groupBy = null;
        $orderBy = 'user_date.date_stamp desc, accrual.time_stamp desc';

        $res = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, false, [], $groupBy, $orderBy );
        
        return $res;
    }

    public function getSumByUserIdAndAccrualPolicyId($user_id, $accrual_policy_id){
        if ( $user_id == '' || $accrual_policy_id == '') {
			return FALSE;
		}


        $table = 'accrual';
        $fields = [DB::raw('SUM(amount) as amount')];
        $joinArr = [
            'user_date_total' => ['user_date_total.id', '=', 'accrual.user_date_total_id'],
        ];

        $whereArr = [
            'accrual.user_id' => $user_id,
            'accrual.accrual_policy_id' => $accrual_policy_id,
            '( (accrual.user_date_total_id is NOT NULL AND user_date_total.id is NOT NULL)
			OR accrual.user_date_total_id IS NULL AND user_date_total.id is NULL )'
        ];
        $total = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, false, [], null,  );

        if ($total === FALSE ) {
			$total = 0;
		}

        return $total;
    }

    public function getByAccrualByUserIdAndTypeIdAndDate($user_id,$type,$date_stamp, $where = NULL, $order = NULL){

        if ( $user_id == '' || $type == '' || $date_stamp == '') {
			return FALSE;
		}
        
        $table = 'accrual';
        $fields = '*';
        $joinArr = [];

        $whereArr = [
            DB::raw("DATE_FORMAT(a.time_stamp,'%Y-%m-%d') = ?", [$date_stamp]),
            'user_id' => $user_id,
            'type' => $type,
        ];        

        $groupBy = null;
        $orderBy = null;

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true, [], $groupBy, $orderBy);

        return $res;
    }

}
?>