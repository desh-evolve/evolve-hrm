<?php

namespace App\Http\Controllers\User;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserDeductionController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->common = new CommonModel();
    }

    public function getByCompanyIdAndUserId(){
        print_r('UserDeductionController->getByCompanyIdAndUserId');exit;
    }
    
}
?>