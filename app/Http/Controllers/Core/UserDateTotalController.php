<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class UserDateTotalController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getWorkedUsersByPayPeriodId( $pay_period_id ){
        if ( $pay_period_id == '' ) {
			return FALSE;
		}

        $table = 'user_date_total';
        $fields = [DB::raw('count(distinct(user_date.user_id)) as count')];
        $joinArr = [
            'user_date' => ['user_date.id', '=', 'user_date_total.user_date_id']
        ];

        $whereArr = [
            ['user_date.pay_period_id', '=', $pay_period_id],
            ['user_date_total.status', '=', '"worked"'],
            ['user_date_total.total_time', '>', 0],
            ['user_date.status', '!=', '"delete"'],
        ];

        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = null;

        $total = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy );
        
        $total = $total[0]->count;

		if ($total === FALSE ) {
			$total = 0;
		}

        return $total;
    }
}

?>