<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class BreakPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view break policy', ['only' => ['index', 'getAllBreakPolicies']]);
        $this->middleware('permission:create break policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:update break policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:delete break policy', ['only' => ['deleteBreakPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.break.index');
    }

    public function getAllBreakPolicies(){
        $breaks = $this->common->commonGetAll('break_policy', '*');
        return response()->json(['data' => $breaks], 200);
    }

    public function deleteBreakPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Break Policy';
        $table = 'break_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createBreakPolicy(Request $request)
    {
        
    }

}