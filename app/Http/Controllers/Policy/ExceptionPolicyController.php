<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class ExceptionPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view exception policy', ['only' => ['index', 'getAllExceptionPolicies']]);
        $this->middleware('permission:create exception policy', ['only' => ['', '', '']]);
        $this->middleware('permission:update exception policy', ['only' => ['', '', '']]);
        $this->middleware('permission:delete exception policy', ['only' => ['']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.exception.index');
    }

    public function getAllExceptionPolicies(){
        
    }



}