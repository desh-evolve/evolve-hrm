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

}
?>