<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class OvertimePolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view overtime policy', ['only' => ['index', 'getAllOvertimePolicies']]);
        $this->middleware('permission:create overtime policy', ['only' => ['form', 'getOvertimeDropdownData', '']]);
        $this->middleware('permission:update overtime policy', ['only' => ['form', 'getOvertimeDropdownData', '']]);
        $this->middleware('permission:delete overtime policy', ['only' => ['deleteOvertimePolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.overtime.index');
    }

    public function form()
    {
        return view('policy.overtime.form');
    }

    public function getOvertimeDropdownData(){
        $ot_types = $this->common->commonGetAll('overtime_types', '*');
        $wage_groups = $this->common->commonGetAll('com_wage_groups', ['id', 'wage_group_name AS name']);
        $pay_stubs = $this->common->commonGetAll('pay_stub_entry_account', '*');
        $accrual_policies = $this->common->commonGetAll('accrual_policy', '*');
        return response()->json([
            'data' => [
                'ot_types' => $ot_types,
                'wage_groups' => $wage_groups,
                'pay_stubs' => $pay_stubs,
                'accrual_policies' => $accrual_policies,
            ]
        ], 200);
    }

    public function getAllOvertimePolicies(){
        $fields = ['overtime_policy.id', 'overtime_policy.name', 'trigger_time', 'max_time', 'rate', 'accrual_policy_id', 'accrual_rate', 'overtime_policy.type_id', 'overtime_types.name AS type'];
        $joinArr = [
            'overtime_types' => ['overtime_types.id', '=', 'overtime_policy.type_id']
        ];
        $overtimes = $this->common->commonGetAll('overtime_policy', $fields, $joinArr);
        return response()->json(['data' => $overtimes], 200);
    }

    public function deleteOvertimePolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Overtime Policy';
        $table = 'overtime_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createOvertimePolicy(Request $request)
    {
        
    }

}