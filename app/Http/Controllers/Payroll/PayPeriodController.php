<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayPeriodController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getById($id){
        $table = 'pay_period';
        $idColumn = 'id';
        $fields = '*';
        $joinArr = [];
        $whereArr = [];
        $exceptDel = true;

        $res = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr, $whereArr, $exceptDel);

        return $res;
    }

    public function getByIdAndCompanyId($id, $com_id){
        //use com_id if necessary later
        $table = 'pay_period';
        $idColumn = 'id';
        $fields = '*';
        $joinArr = [];
        $whereArr = [];
        $exceptDel = true;

        $res = $this->common->commonGetById($id, $idColumn, $table, $fields, $joinArr, $whereArr, $exceptDel);

        return $res;
    }

    public function getByCompanyIdAndStatus($company_id, $status_ids){
        if ( $company_id == '' ) {
			return FALSE;
		}

		if ( $status_ids == '' ) {
			return FALSE;
		}

        $table = 'pay_period';
        $fields = '*';
        $joinArr = [];
        
        $whereArr = [
            //['company_id', '=', $company_id],
            'pay_period.status in ('.implode(',', array_map(fn($id) => "'$id'", $status_ids)).')',
        ];

        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = 'pay_period.transaction_date ASC';

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy);
        
        return $res;

    }

}

?>