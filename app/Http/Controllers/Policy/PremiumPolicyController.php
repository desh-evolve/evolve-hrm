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
        $this->middleware('permission:create premium policy', ['only' => ['form', 'getPremiumDropdownData', 'createPremiumPolicy']]);
        $this->middleware('permission:update premium policy', ['only' => ['form', 'getPremiumDropdownData', 'updatePremiumPolicy', 'getPremiumPolicyById']]);
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
        $branches = $this->common->commonGetAll('com_branches', ['id', 'branch_name AS name']);
        $departments = $this->common->commonGetAll('com_departments', ['id', 'department_name AS name']);
        $br_deps = $this->common->commonGetAll('com_branch_departments', '*');
        return response()->json([
            'data' => [
                'wage_groups' => $wage_groups,
                'pay_stubs' => $pay_stubs,
                'accrual_policies' => $accrual_policies,
                'branches' => $branches,
                'departments' => $departments,
                'br_deps' => $br_deps,
            ]
        ], 200);
    }

    public function getAllPremiumPolicies(){
        $premiums = $this->common->commonGetAll('premium_policy', '*');
        return response()->json(['data' => $premiums], 200);
    }

    public function getPremiumPolicyById(){
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
        /*
        name //input text
        type //select
        start_date //input date
        end_date //input date
        start_time //input text
        end_time //input text
        include_partial_punch //checkbox
        daily_trigger_time 
        weekly_trigger_time

        sun
        mon
        tue
        wed
        thu
        fri
        sat

        daily_trigger_time2
        maximum_no_break_time
        minimum_break_time
        minimum_time_between_shift
        minimum_first_shift_time
        minimum_shift_time
        minimum_time
        maximum_time
        include_meal_policy
        include_break_policy
        pay_type
        rate
        wage_group_id
        pay_stub_entry_account_id
        accrual_policy_id
        premium_policy_id
        */
    }

    public function updatePremiumPolicy(Request $request, $id)
    {
        
    }

}