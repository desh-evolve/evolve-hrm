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
        $this->middleware('permission:create meal policy', ['only' => ['form', 'createMealPolicy', '']]);
        $this->middleware('permission:update meal policy', ['only' => ['form', 'updateMealPolicy', 'getMealPolicyById']]);
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

    public function getMealPolicyById($id){
        $meals = $this->common->commonGetById($id, 'id', 'meal_policy', '*');
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
        try {
            return DB::transaction(function () use ($request) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'auto_detect_type' => 'required|string',
                    'include_lunch_punch_time' => 'nullable|boolean',
                    'trigger_time' => 'nullable|integer',
                    'amount' => 'nullable|integer',
                    'start_window' => 'nullable|integer',
                    'window_length' => 'nullable|integer',
                    'minimum_punch_time' => 'nullable|integer',
                    'maximum_punch_time' => 'nullable|integer',
                ]);

                $table = 'meal_policy';
                $inputArr = [
                    'company_id' => 1, // Replace with dynamic company ID if applicable
                    'name' => $request->name,
                    'type' => $request->type,
                    'auto_detect_type' => $request->auto_detect_type,
                    'include_lunch_punch_time' => $request->include_lunch_punch_time ?? 0,
                    'trigger_time' => $request->trigger_time,
                    'amount' => $request->amount,
                    'start_window' => $request->start_window,
                    'window_length' => $request->window_length,
                    'minimum_punch_time' => $request->minimum_punch_time,
                    'maximum_punch_time' => $request->maximum_punch_time,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $mealPolicyId = $this->common->commonSave($table, $inputArr);

                if ($mealPolicyId) {
                    return response()->json(['status' => 'success', 'message' => 'Meal policy created successfully', 'data' => ['id' => $mealPolicyId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create meal policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateMealPolicy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:250',
                    'type' => 'required|string',
                    'auto_detect_type' => 'required|string',
                    'include_lunch_punch_time' => 'nullable|boolean',
                    'trigger_time' => 'nullable|integer',
                    'amount' => 'nullable|integer',
                    'start_window' => 'nullable|integer',
                    'window_length' => 'nullable|integer',
                    'minimum_punch_time' => 'nullable|integer',
                    'maximum_punch_time' => 'nullable|integer',
                ]);
    
                $table = 'meal_policy';
                $idColumn = 'id';
                $inputArr = [
                    'name' => $request->name,
                    'type' => $request->type,
                    'auto_detect_type' => $request->auto_detect_type,
                    'include_lunch_punch_time' => $request->include_lunch_punch_time ?? 0,
                    'trigger_time' => $request->trigger_time,
                    'amount' => $request->amount,
                    'start_window' => $request->start_window,
                    'window_length' => $request->window_length,
                    'minimum_punch_time' => $request->minimum_punch_time,
                    'maximum_punch_time' => $request->maximum_punch_time,
                    'updated_by' => Auth::user()->id,
                ];
    
                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);
    
                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Meal policy updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update meal policy', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage(), 'data' => []], 500);
        }
    }

}