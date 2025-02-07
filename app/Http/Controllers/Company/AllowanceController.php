<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AllowanceController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getByUserIdAndPayperiodsId($user_id,$payperiod_id, $where = NULL, $order = NULL) {
		if ( $user_id == '') {
			return FALSE;
		}

        $table = 'allowance_data';
        $fields = '*';
        $joinArr = [];

        $whereArr = [
            'user_id' => $user_id,
            'payperiod_id' => $payperiod_id,
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