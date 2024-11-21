<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PremiumPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view premium policy', ['only' => ['index', 'getAllPremiumPolicies']]);
        $this->middleware('permission:create premium policy', ['only' => ['form', 'getPremiumDropdownData', '']]);
        $this->middleware('permission:update premium policy', ['only' => ['form', 'getPremiumDropdownData', '']]);
        $this->middleware('permission:delete premium policy', ['only' => ['deletePremiumPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.premium.index');
    }

    public function form()
    {
        return view('policy.premium.form');
    }

    public function getPremiumDropdownData(){
        $wage_groups = $this->common->commonGetAll('com_wage_groups', ['id', 'wage_group_name AS name']);
        $pay_stubs = $this->common->commonGetAll('pay_stub_entry_account', '*');
        $accrual_policies = $this->common->commonGetAll('accrual_policy', '*');
        return response()->json([
            'data' => [
                'wage_groups' => $wage_groups,
                'pay_stubs' => $pay_stubs,
                'accrual_policies' => $accrual_policies,
            ]
        ], 200);
    }

    public function getAllPremiumPolicies(){
        $premiums = $this->common->commonGetAll('premium_policy', '*');
        return response()->json(['data' => $premiums], 200);
    }

    public function deletePremiumPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Premium Policy';
        $table = 'premium_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createPremiumPolicy(Request $request)
    {
        
    }

}