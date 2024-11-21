<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class AbsencePolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view absence policy', ['only' => ['index', 'getAllAbsencePolicies']]);
        $this->middleware('permission:create absence policy', ['only' => ['form', 'getAbsenceDropdownData', '']]);
        $this->middleware('permission:update absence policy', ['only' => ['form', 'getAbsenceDropdownData', '']]);
        $this->middleware('permission:delete absence policy', ['only' => ['deleteAbsencePolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.absence.index');
    }

    public function form()
    {
        return view('policy.absence.form');
    }

    public function getAbsenceDropdownData(){
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

    public function getAllAbsencePolicies(){
        $absences = $this->common->commonGetAll('absence_policy', '*');
        return response()->json(['data' => $absences], 200);
    }

    public function deleteAbsencePolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Absence Policy';
        $table = 'absence_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createAbsencePolicy(Request $request)
    {
        
    }

}