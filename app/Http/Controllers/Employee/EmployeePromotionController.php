<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class EmployeePromotionController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view user promotion', ['only' => [
            'index',
            'getAllEmployeePromotion',
            'getAllEmployeeList',
            'getEmployeePromotionById',
            'getSingleEmployeePromotion',
        ]]);
        $this->middleware('permission:create user promotion', ['only' => ['createEmployeePromotion']]);
        $this->middleware('permission:update user promotion', ['only' => ['updateEmployeePromotion']]);
        $this->middleware('permission:delete user promotion', ['only' => ['deleteEmployeePromotion']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('user_promotion.index');
    }

    public function createEmployeePromotion(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'user_id' => 'required',
                    'current_designation' => 'required',
                    'new_designation' => 'required',
                    'current_salary' => 'required',
                    'new_salary' => 'required',
                    'effective_date' => 'required',
                ]);

                $table = 'emp_promotions';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'current_designation' => $request->current_designation,
                    'new_designation' => $request->new_designation,
                    'current_salary' => $request->current_salary,
                    'new_salary' => $request->new_salary,
                    'effective_date' => $request->effective_date,
                    'remarks' => $request->remarks,
                    'status' => $request->qualification_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Qualification  added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Qualification', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateEmployeePromotion(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'current_designation' => 'required',
                    'new_designation' => 'required',
                    'current_salary' => 'required',
                    'new_salary' => 'required',
                    'effective_date' => 'required',
                ]);

                $table = 'emp_promotions';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'current_designation' => $request->current_designation,
                    'new_designation' => $request->new_designation,
                    'current_salary' => $request->current_salary,
                    'new_salary' => $request->new_salary,
                    'effective_date' => $request->effective_date,
                    'remarks' => $request->remarks,
                    'status' => $request->qualification_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Promotions updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Promotions', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteEmployeePromotion($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Qualifications';
        $table = 'emp_promotions';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllEmployeePromotion()
    {
        $table = 'emp_promotions';
        $fields = '*';
        $user_promotion = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $user_promotion], 200);
    }

    public function getEmployeePromotionById($id)
    {
        $idColumn = 'user_id';
        $table = 'emp_promotions';
        $fields = '*';
        $user_promotion = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_promotion], 200);
    }
    public function getEmployeeList()
    {

        $users = $this->common->commonGetAll('emp_employees', '*');
        // $users = $this->common->commonGetAll($table, $fields);
        return response()->json([
            // 'data' => [
            //     'users' => $users,
            // ]
            'data' => $users,
        ], 200);
    }

    public function getSingleEmployeePromotion($id)
    {
        $idColumn = 'id';
        $table = 'emp_promotions';
        $fields = '*';
        $user_promotion = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $user_promotion], 200);
    }
}
