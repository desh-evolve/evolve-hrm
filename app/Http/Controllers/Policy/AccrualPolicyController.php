<?php

namespace App\Http\Controllers\Policy;

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
        $this->middleware('permission:view accrual policy', ['only' => ['index', 'getAllAccrualPolicies']]);
        $this->middleware('permission:create accrual policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:update accrual policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:delete accrual policy', ['only' => ['deleteAccrualPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.accrual.index');
    }

    public function form()
    {
        return view('policy.accrual.form');
    }

    public function getAllAccrualPolicies(){
        $accruals = $this->common->commonGetAll('accrual_policy', '*');
        return response()->json(['data' => $accruals], 200);
    }

    public function deleteAccrualPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Accrual Policy';
        $table = 'accrual_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createAccrualPolicy(Request $request)
    {
        
    }

}