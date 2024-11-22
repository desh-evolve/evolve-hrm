<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class HolidayPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view holiday policy', ['only' => ['index', 'getAllHolidayPolicies']]);
        $this->middleware('permission:create holiday policy', ['only' => ['form', 'getHolidayDropdownData', '']]);
        $this->middleware('permission:update holiday policy', ['only' => ['form', 'getHolidayDropdownData', '']]);
        $this->middleware('permission:delete holiday policy', ['only' => ['deleteHolidayPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.holiday.index');
    }

    public function form()
    {
        return view('policy.holiday.form');
    }

    public function getHolidayDropdownData(){
        $rounding_policies = $this->common->commonGetAll('round_interval_policy', '*');
        $absence_policies = $this->common->commonGetAll('absence_policy', '*');
        return response()->json([
            'data' => [
                'rounding_policies' => $rounding_policies,
                'absence_policies' => $absence_policies,
            ]
        ], 200);
    }

    public function getAllHolidayPolicies(){
        $holidays = $this->common->commonGetAll('holiday_policy', '*');
        return response()->json(['data' => $holidays], 200);
    }

    public function deleteHolidayPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Holiday Policy';
        $table = 'holiday_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createHolidayPolicy(Request $request)
    {
        
    }

}