<?php

namespace App\Http\Controllers\Employee;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeBankDetailsController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view employee bank details', ['only' => [
            'index',
            'getBankDetailsByEmpId',
            'getAllEmployee',
            'showBankDetails',
            ]]);

        $this->middleware('permission:create employee bank details', ['only' => ['createBankDetails']]);
        $this->middleware('permission:update employee bank details', ['only' => ['updateBankDetails']]);
        $this->middleware('permission:delete employee bank details', ['only' => ['deleteBankDetails']]);

        $this->common = new CommonModel();
    }


    //pawanee(2024-11-08)
    public function index()
    {
        return view('user_bank.new_index');
    }


    //pawanee(2024-11-08)
    public function showBankDetails($id)
    {
        $idColumn = 'id';
        $table = 'emp_employees';
        $fields = '*';
        $user = $this->common->commonGetById($id, $idColumn, $table, $fields);


        // Check if the user exists
        if (!$user || count($user) === 0) {
            abort(404, 'Employee not found.');
        }

        // Fetch bank details associated with the user
        $bankDetails = $this->common->commonGetById($id, 'user_id', 'emp_bank_details', '*');

        // Pass the user and bank details to the view
        return view('user_bank.edit', ['user' => $user[0], 'bankDetails' => $bankDetails, ]);
    }


    //pawanee(2024-11-08)
    public function createBankDetails(Request $request){
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'user_id' => 'required',
                    'bank_code' => 'required',
                    'bank_name' => 'required|string',
                    'bank_branch' => 'required',
                    'account_number' => 'required|numeric',
                ]);

                $table = 'emp_bank_details';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'bank_code' => $request->bank_code,
                    'bank_name' => $request->bank_name,
                    'bank_branch' => $request->bank_branch,
                    'account_number' => $request->account_number,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Bank Details Added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to Add Bank Details', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }



    //pawanee(2024-11-08)
    public function updateBankDetails(Request $request, $id){
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'user_id' => 'required',
                    'bank_code' => 'required',
                    'bank_name' => 'required|string',
                    'bank_branch' => 'required',
                    'account_number' => 'required|numeric',
                ]);

                $table = 'emp_bank_details';
                $idColumn = 'id';
                $inputArr = [
                    'user_id' => $request->user_id,
                    'bank_code' => $request->bank_code,
                    'bank_name' => $request->bank_name,
                    'bank_branch' => $request->bank_branch,
                    'account_number' => $request->account_number,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Bank Details updateded successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update Bank Details', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }



    //pawanee(2024-11-08)
    public function deleteBankDetails($id){
        $whereArr = ['id' => $id];
        $title = 'Bank Details';
        $table = 'emp_bank_details';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }



    //pawanee(2024-11-08)
    public function getBankDetailsByEmpId($id){
        $idColumn = 'user_id';
        $table = 'emp_bank_details';
        $fields = '*';
        $bankDetails = $this->common->commonGetById($id, $idColumn, $table, $fields);

        return response()->json(['data' => $bankDetails], 200);
    }



    public function getAllEmployee()
    {
        $table = 'emp_employees';
        $fields = '*';
        $users = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $users], 200);
    }

}
