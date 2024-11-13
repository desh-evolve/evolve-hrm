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
        $this->middleware('permission:create exception policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:update exception policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:delete exception policy', ['only' => ['deleteExceptionPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.exception.index');
    }

    public function form()
    {
        return view('policy.exception.form');
    }

    public function getAllExceptionPolicies(){
        $exceptions = $this->common->commonGetAll('exception_policy_control', '*');
        return response()->json(['data' => $exceptions], 200);
    }

    public function deleteExceptionPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Exception Policy';
        $table = 'exception_policy_control';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createExceptionPolicy(Request $request)
    {
        
    }

}