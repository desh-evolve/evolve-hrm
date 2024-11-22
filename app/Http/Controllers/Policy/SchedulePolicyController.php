<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class SchedulePolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view schedule policy', ['only' => ['index', 'getAllSchedulePolicies']]);
        $this->middleware('permission:create schedule policy', ['only' => ['form', 'getScheduleDropdownData', '']]);
        $this->middleware('permission:update schedule policy', ['only' => ['form', 'getScheduleDropdownData', '']]);
        $this->middleware('permission:delete schedule policy', ['only' => ['deleteSchedulePolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.schedule.index');
    }

    public function getScheduleDropdownData(){
        $punch_types = $this->common->commonGetAll('round_interval_punch_types', '*');
        return response()->json([
            'data' => [
                'punch_types' => $punch_types,
            ]
        ], 200);
    }

    public function getAllSchedulePolicies(){
        $fields = ['round_interval_policy.id', 'round_interval_policy.name', 'round_interval_policy.punch_type_id', 'round_type', 'round_interval', 'strict', 'grace', 'round_interval_punch_types.name AS punch_type'];
        $joinArr = [
            'round_interval_punch_types' => ['round_interval_punch_types.id', '=', 'round_interval_policy.punch_type_id']
        ];
        $schedules = $this->common->commonGetAll('round_interval_policy', $fields, $joinArr);
        return response()->json(['data' => $schedules], 200);
    }

    public function deleteSchedulePolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Schedule Policy';
        $table = 'round_interval_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createSchedulePolicy(Request $request)
    {
        
    }

}