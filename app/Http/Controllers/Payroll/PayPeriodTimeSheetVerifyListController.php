<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayPeriodTimeSheetVerifyListController extends Controller
{
    private $common = null;

    public function __construct()
    {
        //$this->middleware('permission:view pay stub account', ['only' => ['']]);
        $this->common = new CommonModel();
    }

    public function getByPayPeriodIdAndCompanyId($pay_period_id, $company_id){
        if ( $pay_period_id == '' || $company_id == '') {
			return FALSE;
		}

        $table = 'pay_period_time_sheet_verify';
        $fields = 'pay_period_time_sheet_verify.*';
        $joinArr = [
           'emp_employees' => ['emp_employees.user_id', '=', 'pay_period_time_sheet_verify.user_id'] 
        ];
        
        $whereArr = [
            //['emp_employees.company_id', '=', $company_id],
            ['pay_period_time_sheet_verify.pay_period_id', '=', $pay_period_id],
            ['emp_employees.status', '!=', '"delete"'],
        ];

        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = null;

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy);
        
        return $res;

    }

}
?>