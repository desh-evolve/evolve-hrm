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
        $this->middleware('permission:view schedule policy', ['only' => ['index', 'getAllSchedulePolicies', 'getScheduleDropdownData']]);
        $this->middleware('permission:create schedule policy', ['only' => ['', '', '']]);
        $this->middleware('permission:update schedule policy', ['only' => ['', '', '']]);
        $this->middleware('permission:delete schedule policy', ['only' => ['deleteSchedulePolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.schedule.index');
    }

    public function getScheduleDropdownData(){
        $absence_policy = $this->common->commonGetAll('absence_policy', '*');
        $break_policy = $this->common->commonGetAll('break_policy', '*');
        $meal_policy = $this->common->commonGetAll('meal_policy', '*');
        $overtime_policy = $this->common->commonGetAll('overtime_policy', '*');
        return response()->json([
            'data' => [
                'absence_policy' => $absence_policy,
                'break_policy' => $break_policy,
                'meal_policy' => $meal_policy,
                'overtime_policy' => $overtime_policy,
            ]
        ], 200);
    }

    public function getAllSchedulePolicies(){
        $fields = ['schedule_policy.*', 'meal_policy.name AS meal_policy', 'overtime_policy.name AS overtime_policy', 'absence_policy.name AS absence_policy'];
        $joinArr = [
            'meal_policy' => ['meal_policy.id', '=', 'schedule_policy.meal_policy_id'],
            'overtime_policy' => ['overtime_policy.id', '=', 'schedule_policy.over_time_policy_id'],
            'absence_policy' => ['absence_policy.id', '=', 'schedule_policy.absence_policy_id'],
        ];
        $schedules = $this->common->commonGetAll('schedule_policy', $fields, $joinArr);
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