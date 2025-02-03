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

    public function getByPolicyGroupUserIdAndDate(){
        print_r('HolidayController->getByPolicyGroupUserIdAndDate');exit;
    }

}

?>