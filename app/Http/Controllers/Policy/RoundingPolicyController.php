<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class RoundingPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view rounding policy', ['only' => ['index', 'getAllRoundingPolicies']]);
        $this->middleware('permission:create rounding policy', ['only' => ['form', 'getRoundingDropdownData', '']]);
        $this->middleware('permission:update rounding policy', ['only' => ['form', 'getRoundingDropdownData', '']]);
        $this->middleware('permission:delete rounding policy', ['only' => ['deleteRoundingPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.rounding.index');
    }

    public function getRoundingDropdownData(){
        $punch_types = $this->common->commonGetAll('round_interval_punch_types', '*');
        return response()->json([
            'data' => [
                'punch_types' => $punch_types,
            ]
        ], 200);
    }

    public function getAllRoundingPolicies(){
        $fields = ['round_interval_policy.id', 'round_interval_policy.name', 'round_interval_policy.punch_type_id', 'round_type', 'round_interval', 'strict', 'grace', 'round_interval_punch_types.name AS punch_type'];
        $joinArr = [
            'round_interval_punch_types' => ['round_interval_punch_types.id', '=', 'round_interval_policy.punch_type_id']
        ];
        $roundings = $this->common->commonGetAll('round_interval_policy', $fields, $joinArr);
        return response()->json(['data' => $roundings], 200);
    }

    public function deleteRoundingPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Rounding Policy';
        $table = 'round_interval_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createRoundingPolicy(Request $request)
    {
        
    }

}