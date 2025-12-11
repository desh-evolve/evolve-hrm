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
            'getAccrualById',
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

    public function getCompanyDeductionDropdownData()
    {
        $type = [
            ['id' => 1, 'name' => 'Awarded', 'value' => 'awarded'],
            ['id' => 2, 'name' => 'Un-Awarded', 'value' => 'un-deduction'],
            ['id' => 3, 'name' => 'Gift', 'value' => 'gift'],
            ['id' => 4, 'name' => 'Paid Out', 'value' => 'paid-out'],
            ['id' => 5, 'name' => 'Rollover Adjustment', 'value' => 'rollover-adjustment'],
            ['id' => 6, 'name' => 'Initial Balance', 'value' => 'initial-balance'],
            ['id' => 7, 'name' => 'Other', 'value' => 'other'],
        ];

        $users = $this->common->commonGetAll('emp_employees', '*');
        $accrual_policy = $this->common->commonGetAll('accrual_policy', '*');


        //type => create table

        return response()->json([
            'data' => [
                'accrual_policy' => $accrual_policy,
                'users' => $users,
                'type' => $type,
            ]
        ], 200);
    }

    // public function createAccrualBalance(Request $request)
    // {
    //     try {
    //         return DB::transaction(function () use ($request) {
    //             // $request->validate([
    //             //     'user_id' => 'required',
    //             //     'accrual_policy_id' => 'required|string',
    //             //     // 'amount' => 'nullable|regex:/^\d{1,14}(\.\d{1,4})?$/',
    //             // ]);
    //             $totalExistingAmount = DB::table('accrual')
    //                 ->where('accrual_policy_id', $request->accrual_policy_id)
    //                 ->where('user_id', $request->user_id)
    //                 ->where('type', $request->type)
    //                 ->sum('amount');

    //             // Add the total existing amount to the new request amount
    //             if ($totalExistingAmount > 0) {
    //                 $finalAmount = $request->amount + $totalExistingAmount;
    //             } else {
    //                 $finalAmount = $request->amount;
    //             }

    //             $id = DB::table('accrual_balance')
    //                 ->select('id')
    //                 ->where('accrual_policy_id', $request->accrual_policy_id)
    //                 ->where('user_id', $request->user_id);

    //             $table = 'accrual_balance';
    //             if (!$id) {
    //                 $inputArr = [
    //                     'user_id' => $request->user_id,
    //                     'accrual_policy_id' => $request->accrual_policy_id,
    //                     'balance' => $finalAmount,
    //                     'banked_ytd' => $request->banked_ytd ?: "0",
    //                     'used_ytd' => $request->used_ytd ?: "0",
    //                     'awarded_ytd' => $request->awarded_ytd ?: "0",
    //                     'status' => $request->accrual_status,
    //                     'created_by' => Auth::user()->id,
    //                     'updated_by' => Auth::user()->id,
    //                 ];

    //                 $insertId = $this->common->commonSave($table, $inputArr);

    //                 if (!$insertId) {
    //                     return response()->json(['status' => 'error', 'message' => 'Failed to create Accrual Balance'], 500);
    //                 }
    //             }


    //             // Save associated policies
    //             $this->saveAccrualType($request);

    //             return response()->json(['status' => 'success', 'message' => 'Accrual Balance create successfully', 'data' => ['id' => '1']], 200);
    //         });
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
    //     }
    // }

    public function createAccrualBalance(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // Validate the request
                $request->validate([
                    'user_id' => 'required|integer',
                    'accrual_policy_id' => 'required|string',
                    'amount' => 'nullable|numeric',
                ]);

                // Calculate the total existing amount from the accrual table
                $totalExistingAmount = DB::table('accrual')
                    ->where('accrual_policy_id', $request->accrual_policy_id)
                    ->where('user_id', $request->user_id)
                    ->sum('amount');

                // Calculate the final amount
                $finalAmount = $totalExistingAmount > 0
                    ? $request->amount + $totalExistingAmount
                    : $request->amount;

                // Check if an entry already exists in the accrual_balance table
                $existingAccrualBalance = DB::table('accrual_balance')
                    ->where('accrual_policy_id', $request->accrual_policy_id)
                    ->where('user_id', $request->user_id)
                    ->first();

                $table = 'accrual_balance';
                if (!$existingAccrualBalance) {
                    // Insert a new record into the accrual_balance table
                    $inputArr = [
                        'user_id' => $request->user_id,
                        'accrual_policy_id' => $request->accrual_policy_id,
                        'balance' => $finalAmount,
                        'banked_ytd' => $request->banked_ytd ?: 0,
                        'used_ytd' => $request->used_ytd ?: 0,
                        'awarded_ytd' => $request->awarded_ytd ?: 0,
                        'status' => $request->accrual_status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];

                    $insertId = DB::table($table)->insertGetId($inputArr);

                    if (!$insertId) {
                        throw new \Exception('Failed to create Accrual Balance');
                    }
                } else {
                    // Update the existing record in accrual_balance
                    DB::table('accrual_balance')
                        ->where('id', $existingAccrualBalance->id)
                        ->update([
                            'balance' => $finalAmount,
                            'banked_ytd' => $request->banked_ytd ?: 0,
                            'used_ytd' => $request->used_ytd ?: 0,
                            'awarded_ytd' => $request->awarded_ytd ?: 0,
                            'status' => $request->accrual_status,
                            'updated_by' => Auth::user()->id,
                        ]);
                }

                // Save associated policies
                $this->saveAccrualType($request);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Accrual Balance created/updated successfully',
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }


    // public function updateAccrualBalance(Request $request, $id)
    // {
    //     try {
    //         return DB::transaction(function () use ($request, $id) {

    //             $request->validate([
    //                 'user_id' => 'required',
    //                 'accrual_policy_id' => 'required|string',
    //                 'accrual_status' => 'required',
    //             ]);

    //             $table = 'accrual';
    //             $idColumn = 'id';
    //             $inputArr = [
    //                 'user_id' =>  $request->user_id,
    //                 'accrual_policy_id' =>  $request->accrual_policy_id,
    //                 'type' =>  $request->type,
    //                 'user_date_total_id' =>  $request->user_date_total_id,
    //                 'time_stamp' =>  $request->time_stamp,
    //                 'amount' =>  $request->amount,
    //                 'leave_requset_id' =>  $request->leave_requset_id ?: "0",
    //                 'status' =>  $request->accrual_status,
    //                 'updated_by' => Auth::user()->id,

    //             ];
    //             $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);


    //             if ($insertId) {
    //                 return response()->json(['status' => 'success', 'message' => 'Pay Stub Entry Account Link updated successfully', 'data' => ['id' => $insertId]], 200);
    //             } else {
    //                 return response()->json(['status' => 'error', 'message' => 'Failed updating Pay Stub Entry Account Link', 'data' => []], 500);
    //             }
    //         });
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
    //     }
    // }

    public function updateAccrualBalance(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'accrual_policy_id' => 'required|string',
                    'accrual_status' => 'required',
                ]);

                $table = 'accrual';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' =>  $request->user_id,
                    'accrual_policy_id' =>  $request->accrual_policy_id,
                    'type' =>  $request->type,
                    'user_date_total_id' =>  $request->user_date_total_id,
                    'time_stamp' =>  $request->time_stamp,
                    'amount' =>  $request->amount,
                    'leave_requset_id' =>  $request->leave_requset_id ?: "0",
                    'status' =>  $request->accrual_status,
                    'updated_by' => Auth::user()->id,
                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {

                    // Calculate the total existing amount from the accrual table
                    $totalExistingAmount = DB::table('accrual')
                        ->where('accrual_policy_id', $request->accrual_policy_id)
                        ->where('user_id', $request->user_id)
                        ->sum('amount');

                    DB::table('accrual_balance')
                        ->where('accrual_policy_id', $request->accrual_policy_id)
                        ->where('user_id', $request->user_id)
                        ->update(['balance' => $totalExistingAmount]);

                    return response()->json(['status' => 'success', 'message' => 'Accrual updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Accrual', 'data' => []], 500);
                }
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
                'leave_requset_id' =>  $request->leave_requset_id ?: "0",
                'status' =>  $request->accrual_status,
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
        $title = 'Accrual';
        $table = 'accrual';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllAccrualBalance($userId)
    {
        $table = 'accrual_balance';
        $fields = '*';
        $fields = ['accrual_balance.*', 'accrual_policy.name AS accrual_policy_name'];
        $joinArr = [
            'accrual_policy' => ['accrual_policy.id', '=', 'accrual_balance.accrual_policy_id'],
        ];
        $whereArr = [
            'user_id' => $userId,
        ];
        $accrual_balance = $this->common->commonGetAll($table, $fields, $joinArr, $whereArr);
        return response()->json(['data' => $accrual_balance], 200);
    }

    public function getAccrualById($id)
    {
        $idColumn = 'id';
        $table = 'accrual';
        $fields = '*';
        $accrual_balance = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $accrual_balance], 200);
    }

    public function getAccrualTypeByAccrualId($id)
    {
        // dd($id);
        $idColumn = 'accrual_policy_id';
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
