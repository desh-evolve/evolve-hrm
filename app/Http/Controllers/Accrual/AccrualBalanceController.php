<?php

namespace App\Http\Controllers\Accrual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;



class AccrualBalanceController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view accrual balance', ['only' => [
            'index',
            'getAllAccrualBalance',
            'getAccrualBalanceById',
        ]]);
        $this->middleware('permission:create accrual balance', ['only' => ['createAccrualBalance']]);
        $this->middleware('permission:update accrual balance', ['only' => ['updateAccrualBalance']]);
        $this->middleware('permission:delete accrual balance', ['only' => ['deleteAccrualBalance']]);


        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('attendance.accrual.index');
    }

    public function createAccrualBalance(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'user_id' => 'required',
                    'accrual_policy_id' => 'required|string',
                    'balance' => 'nullable|regex:/^\d{1,14}(\.\d{1,4})?$/',
                ]);

                $table = 'accrual_balance';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'accrual_policy_id' => $request->accrual_policy_id,
                    'balance' => $request->balance,
                    'banked_ytd' => $request->banked_ytd ?: "",
                    'used_ytd' => $request->used_ytd ?: "",
                    'awarded_ytd' => $request->awarded_ytd ?: "",
                    'status' => $request->accrual_balance_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if (!$insertId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create Accrual Balance'], 500);
                }

                // Save associated policies
                $this->saveAccrualType($request);

                return response()->json(['status' => 'success', 'message' => 'Accrual Balance create successfully', 'data' => ['id' => '1']], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateAccrualBalance(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'accrual_policy_id' => 'required|string',
                    'balance' => 'nullable|regex:/^\d{1,14}(\.\d{1,4})?$/',
                ]);

                $table = 'accrual_balance';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'accrual_policy_id' => $request->accrual_policy_id,
                    'balance' => $request->balance,
                    'banked_ytd' => $request->banked_ytd ?: "",
                    'used_ytd' => $request->used_ytd ?: "",
                    'awarded_ytd' => $request->awarded_ytd ?: "",
                    'status' => $request->accrual_balance_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if (!$insertId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to Update Accrual Balance'], 500);
                }
                // Save associated policies
                $this->saveAccrualType($request);

                return response()->json(['status' => 'success', 'message' => 'Accrual Balance updated successfully', 'data' => ['id' => '1']], 200);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    private function saveAccrualType($request)
    {
        if (!empty($request->accrual_policy_id)) {

            // Retrieve accrual amounts before deletion
            $totalExistingAmount = DB::table('accrual')
                ->where('accrual_policy_id', $request->accrual_policy_id)
                ->where('user_id', $request->user_id)
                ->where('type', $request->type)
                ->sum('amount');

            // Add the total existing amount to the new request amount
            if ($totalExistingAmount > 0) {
                $finalAmount = $request->amount + $totalExistingAmount;
            } else {
                $finalAmount = $request->amount;
            }

            // Delete all the same existing accruals before saving
            DB::table('accrual')
                ->where('accrual_policy_id', $request->accrual_policy_id)
                ->where('user_id', $request->user_id)
                ->where('type', $request->type)
                ->delete();

            // Prepare bulk insert data
            $inputArr = [
                'user_id' =>  $request->user_id,
                'accrual_policy_id' =>  $request->accrual_policy_id,
                'type' =>  $request->type,
                'user_date_total_id' =>  $request->user_date_total_id,
                'time_stamp' =>  $request->time_stamp,
                'amount' =>  $finalAmount,
                'leave_requset_id' =>  $request->leave_requset_id,
                'status' =>  $request->status,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];

            // Insert all users in a single query
            DB::table('accrual')->insert($inputArr);
        }
    }

    public function deleteAccrualBalance($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Accrual Balance';
        $table = 'accrual_balance';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllAccrualBalance()
    {
        $table = 'accrual_balance';
        $fields = '*';
        $accrual_balance = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $accrual_balance], 200);
    }

    public function getAccrualBalanceById($id)
    {
        $idColumn = 'id';
        $table = 'accrual_balance';
        $fields = '*';
        $accrual_balance = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $accrual_balance], 200);
    }

    public function getAccrualTypeByAccrualId($id)
    {
        $idColumn = 'id';
        $table = 'accrual';
        $fields = '*';
        $accrual_type = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $accrual_type], 200);
    }

    public function getByUserIdAndAccrualPolicyId($user_id, $accrual_policy_id)
    {
        if ($user_id == '') {
            return FALSE;
        }

        if ($accrual_policy_id == '') {
            return FALSE;
        }

        $table = 'accrual_balance';
        $fields = '*';
        $joinArr = [];

        $whereArr = [
            'user_id' => $user_id,
            'accrual_policy_id' => $accrual_policy_id,
        ];

        $groupBy = null;
        $orderBy = 'id desc';

        $res = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr, false, [], $groupBy, $orderBy);

        return $res;
    }
}
