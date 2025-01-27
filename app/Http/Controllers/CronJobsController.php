<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AccrualPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:run cron', ['only' => ['userDateCron']]);

        $this->common = new CommonModel();
    }

    public function userDateCron(){
        $allUsers = [];

        
    }


}