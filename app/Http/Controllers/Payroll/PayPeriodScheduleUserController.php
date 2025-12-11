<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayPeriodScheduleUserController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    function getByPayPeriodScheduleId($id) {
		if ( $id == '') {
			return FALSE;
		}

        $table = 'pay_period_schedule_user';
        $fields = '*';
        $joinArr = [
            'pay_period_schedule' => ['pay_period_schedule.id', '=', 'pay_period_schedule_user.pay_period_schedule_id']
        ];
        $whereArr = [['pay_period_schedule_id', '=', $id]];
        $exceptDel = true;
        $connections = [];
        $groupBy = null;
        $orderBy = 'pay_period_schedule_user.user_id';

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, $exceptDel, $connections, $groupBy, $orderBy);

        return $res;
	}

}
?>