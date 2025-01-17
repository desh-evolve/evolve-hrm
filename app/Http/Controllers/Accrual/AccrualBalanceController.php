<?php

namespace App\Http\Controllers\Accrual;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AccrualBalanceController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        //$this->middleware('permission:apply leaves', ['only' => ['', '']]);

        $this->common = new CommonModel();
    }

    public function getByUserIdAndAccrualPolicyId($user_id, $accrual_policy_id){
        if ( $user_id == '') {
			return FALSE;
		}

		if ( $accrual_policy_id == '') {
			return FALSE;
		}

        $table = 'accrual_balance';
        $fields = '*';
        $joinArr = [];

        $whereArr = [
            'user_id' => $user_id,
            'accrual_policy_id' => $accrual_policy_id,
        ];

        $groupBy = null;
        $orderBy = 'id desc';

        $res = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, false, [], $groupBy, $orderBy );
        
        return $res;
    }

}
?>