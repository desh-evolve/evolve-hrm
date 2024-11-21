<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class MealPolicyController extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->middleware('permission:view meal policy', ['only' => ['index', 'getAllMealPolicies']]);
        $this->middleware('permission:create meal policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:update meal policy', ['only' => ['form', '', '']]);
        $this->middleware('permission:delete meal policy', ['only' => ['deleteMealPolicy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('policy.meal.index');
    }

    public function getAllMealPolicies(){
        $meals = $this->common->commonGetAll('meal_policy', '*');
        return response()->json(['data' => $meals], 200);
    }

    public function deleteMealPolicy($id){
        $whereArr = ['id' => $id];
        $title = 'Meal Policy';
        $table = 'meal_policy';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function createMealPolicy(Request $request)
    {
        
    }

}