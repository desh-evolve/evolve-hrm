<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;
use Carbon\Carbon;

class ExceptionController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        //$this->middleware('permission:apply leaves', ['only' => ['', '']]);

        $this->common = new CommonModel();

    }

    public function getSumExceptionsByPayPeriodIdAndBeforeDate($pay_period_id, $before_epoch){
        
        if ( $pay_period_id == '' ) {
			return FALSE;
		}

		if ( $before_epoch == '' ) {
			return FALSE;
		}

        $before_epoch = Carbon::parse($before_epoch)->format('Y-m-d');

        $table = 'exception';
        $fields = [DB::raw('exception_policy.severity as severity, count(*) as count')];

        $joinArr = [
            'user_date' => ['user_date.id', '=', 'exception.user_date_id'],
            'emp_employees' => ['emp_employees.user_id', '=', 'user_date.user_id'],
            'exception_policy' => ['exception_policy.id', '=', 'exception.exception_policy_id'],
            'pay_period' => ['pay_period.id', '=', 'user_date.pay_period_id'],
        ];
        
        $whereArr = [
            ['pay_period.id', '=', $pay_period_id],
            ['exception.type', '=', '"active"'],
            ['user_date.date_stamp', '<=', '"'.$before_epoch.'"'],
        ];

        $exceptDel = false;
        $connections = [];
        $groupBy = 'exception_policy.severity';
        $orderBy = 'exception_policy.severity desc';

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy);
        
        return $res;
    }
}
?>