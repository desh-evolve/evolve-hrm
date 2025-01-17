<?php

namespace App\Http\Controllers\Accrual;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class UserDateController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        //$this->middleware('permission:apply leaves', ['only' => ['', '']]);

        $this->common = new CommonModel();
    }

    public function getByUserIdAndDate($user_id, $date){
        if ( $user_id == '' ) {
			return FALSE;
		}

		if ( $date == '' OR $date <= 0 ) {
			return FALSE;
		}	

        $table = 'user_date';
        $fields = '*';
        $joinArr = [];

        $whereArr = [
            'user_id' => $user_id,
            'date_stamp' => $date,
        ];

        $groupBy = null;
        $orderBy = 'id asc';

        $res = $this->common->commonGetAll( $table, $fields, $joinArr, $whereArr, false, [], $groupBy, $orderBy );
        
        return $res;
    }

}

?>