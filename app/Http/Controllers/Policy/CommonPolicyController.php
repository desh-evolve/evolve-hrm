<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class CommonPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    // check unassigned policies in policy groups
    public function checkInPolicyGroups($policyName)
    {
        return 'hi';
    }
}