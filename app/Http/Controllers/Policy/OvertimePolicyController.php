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
        return response()->json([
            'data' => [
                'ot_types' => $ot_types,
            ]
        ], 200);
    }

    public function getAllOvertimePolicies(){
        $overtimes = $this->common->commonGetAll('overtime_policy', '*');
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