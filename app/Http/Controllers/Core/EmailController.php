<?php

namespace App\Http\Controllers\Accrual;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmailController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        //$this->middleware('permission:apply leaves', ['only' => ['', '']]);

        $this->common = new CommonModel();
    }

    public function sendEmail($subject, $body, $from, $to){
        echo 'email sent';
    }

}

?>