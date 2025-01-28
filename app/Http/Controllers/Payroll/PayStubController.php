<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayStubController extends Controller
{
    private $common = null;

    public function __construct()
    {
        //$this->middleware('permission:view pay stub account', ['only' => ['']]);
        $this->common = new CommonModel();
    }

    public function getByPayPeriodId( $id ){

        if ( $id == '' ) {
			return FALSE;
		}

        $table = 'pay_stub';
        $fields = '*';
        $joinArr = [];

        $whereArr = [['pay_stub.pay_period_id', '=', $id]];

        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = null;

        $res = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy );

        return $res;
    }

    function getByPayperiodsIdAndUserId($pay_period_id,$user_id)
    {
        
        if ( $pay_period_id == '') {
            return FALSE;
        }
            
        if ( $user_id == '') {
            return FALSE;
        }
            
        $table = 'pay_stub';
        $fields = 'pay_stub.*';
        $joinArr = [
            'emp_employees' => ['emp_employees.user_id', '=', 'pay_stub.user_id']
        ];
        $whereArr = [
            ['pay_period_id', '=', $pay_period_id],
            ['user_id', '=', $user_id],
            ['emp_employees.status', '!=', 'delete']
        ];
        $exceptDel = true;
        $groupBy = null;
        $orderBy = null;

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $groupBy, $orderBy);
        
        return $res;
            
            
    }

}
?>