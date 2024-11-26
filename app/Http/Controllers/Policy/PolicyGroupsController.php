<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PolicyGroupsController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view policy group', ['only' => ['index', 'getAllPolicyGroups']]);
        $this->middleware('permission:create policy group', ['only' => ['form', 'getPolicyGroupDropdownData', '']]);
        $this->middleware('permission:update policy group', ['only' => ['form', 'getPolicyGroupDropdownData', '']]);
        $this->middleware('permission:delete policy group', ['only' => ['deletePolicyGroup']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.policy_groups.index');
    }

    public function form()
    {
        return view('policy.policy_groups.form');
    }

    public function getPolicyGroupDropdownData(){
        $overtime_policies = $this->common->commonGetAll('overtime_policy', ['id', 'name']);
        $rounding_policies = $this->common->commonGetAll('round_interval_policy', ['id', 'name']);
        $meal_policies = $this->common->commonGetAll('meal_policy', ['id', 'name']);
        $break_policies = $this->common->commonGetAll('break_policy', ['id', 'name']);
        $accrual_policies = $this->common->commonGetAll('accrual_policy', ['id', 'name']);
        $premium_policies = $this->common->commonGetAll('premium_policy', ['id', 'name']);
        $holiday_policies = $this->common->commonGetAll('holiday_policy', ['id', 'name']);
        $exception_policies = $this->common->commonGetAll('exception_policy_control', ['id', 'name']);
        $employees = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        return response()->json([
            'data' => [
                'overtime_policies' => $overtime_policies,
                'rounding_policies' => $rounding_policies,
                'meal_policies' => $meal_policies,
                'break_policies' => $break_policies,
                'accrual_policies' => $accrual_policies,
                'premium_policies' => $premium_policies,
                'holiday_policies' => $holiday_policies,
                'exception_policies' => $exception_policies,
                'employees' => $employees,
            ]
        ], 200);
    }

    public function getAllPolicyGroups(){
        $premiums = $this->common->commonGetAll('policy_group', '*');
        return response()->json(['data' => $premiums], 200);
    }

    public function deletePolicyGroup($id){
        $whereArr = ['id' => $id];
        $title = 'Policy Group';
        $table = 'policy_group';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createPolicyGroup(Request $request)
    {
        
    }

}