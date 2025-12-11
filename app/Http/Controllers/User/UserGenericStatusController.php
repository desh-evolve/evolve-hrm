<?php

namespace App\Http\Controllers\User;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserGenericStatusController extends Controller
{
    private $common = null;
    static protected $static_queue = NULL;
    protected $table = 'user_generic_status';
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    static function queueGenericStatus($label, $status, $description = NULL, $link = NULL ) {
		echo 'Add Generic Status row to queue... Label: '. $label .' Status: '. $status;
		$arr = array(
					'label' => $label,
					'status' => $status,
					'description' => $description,
					'link' => $link
					);

		self::$static_queue[] = $arr;

		return TRUE;
	}
    
}
?>