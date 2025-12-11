<?php

namespace App\Http\Controllers\Holiday;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getByPolicyGroupUserIdAndDate($user_id, $date, $where = NULL, $order = NULL) {
        if ($user_id == '' || $date == '') {
            return FALSE;
        }
    
        $table = 'holidays';
        $fields = 'holidays.*';
    
        $joinArr = [
            'policy_group_users' => ['policy_group_users.user_id', '=', 'holidays.user_id'],
            'policy_group' => ['policy_group.id', '=', 'policy_group_users.policy_group_id'],
            'holiday_policy' => ['holiday_policy.id', '=', 'holidays.holiday_policy_id'],
        ];
    
        $whereArr = [
            DB::raw("DATE(holidays.date_stamp) = ?"), [$date],
            'policy_group_users.user_id' => $user_id,
            ['holiday_policy.status', '!=', 'delete'],
            ['holidays.status', '!=', 'delete'],
            ['policy_group.status', '!=', 'delete'],
        ];
    
        $groupBy = null;
        $orderBy = $order ?? 'holiday_policy.type_id asc, holiday_policy.trigger_time desc';
    
        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, true, [], $groupBy, $orderBy);
    
        return $res;
    }
    


}

?>