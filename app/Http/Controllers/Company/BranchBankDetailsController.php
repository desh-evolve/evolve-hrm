<?php

namespace App\Http\Controllers\Company;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchBankDetailsController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view branch bank details', ['only' => [
            'index',
            'getBankDetailsByBranchId',
            'getBankDetailsSingleBranch',
            ]]);

        $this->middleware('permission:create branch bank details', ['only' => ['createBankDetails']]);
        $this->middleware('permission:update branch bank details', ['only' => ['updateBankDetails']]);
        $this->middleware('permission:delete branch bank details', ['only' => ['deleteBankDetails']]);

        $this->common = new CommonModel();
    }



     //pawanee(2024-11-18)
     public function index($id)
    {
        $idColumn = 'id';
        $table = 'com_branches';
        $fields = '*';
        $branch = $this->common->commonGetById($id, $idColumn, $table, $fields);


        // Check if the branch exists
        if (!$branch || count($branch) === 0) {
            abort(404, 'Branch not found.');
        }

        // Fetch bank details associated with the branch
        $bankDetails = $this->common->commonGetById($id, 'branch_id', 'com_branch_bank_details', '*');

        // Pass the branch and bank details to the view
        return view('company.branch.branch_bank', ['branch' => $branch[0], 'bankDetails' => $bankDetails, ]);
    }


    //pawanee(2024-11-18)
    public function createBankDetails(Request $request){
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'branch_id' => 'required',
                    'bank_code' => 'required',
                    'bank_name' => 'required|string',
                    'bank_branch' => 'required',
                    'bank_account' => 'required|numeric',
                ]);

                $table = 'com_branch_bank_details';
                $inputArr = [
                    'branch_id' => $request->branch_id,
                    'bank_code' => $request->bank_code,
                    'bank_name' => $request->bank_name,
                    'bank_branch' => $request->bank_branch,
                    'bank_account' => $request->bank_account,
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



    //pawanee(2024-11-18)
    public function updateBankDetails(Request $request, $id){
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'branch_id' => 'required',
                    'bank_code' => 'required',
                    'bank_name' => 'required|string',
                    'bank_branch' => 'required',
                    'bank_account' => 'required|numeric',
                ]);

                $table = 'com_branch_bank_details';
                $idColumn = 'id';
                $inputArr = [
                    'branch_id' => $request->branch_id,
                    'bank_code' => $request->bank_code,
                    'bank_name' => $request->bank_name,
                    'bank_branch' => $request->bank_branch,
                    'bank_account' => $request->bank_account,
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



    //pawanee(2024-11-18)
    public function deleteBankDetails($id){
        $whereArr = ['id' => $id];
        $title = 'Branch Bank Details';
        $table = 'com_branch_bank_details';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }



    //pawanee(2024-11-18)
    public function getBankDetailsByBanchId($id){
        $idColumn = 'branch_id';
        $table = 'com_branch_bank_details';
        $fields = '*';
        $bankDetails = $this->common->commonGetById($id, $idColumn, $table, $fields);

        return response()->json(['data' => $bankDetails], 200);
    }



     //pawanee(2024-11-18)
     public function getBankDetailsSingleBranch($id)
     {
         $idColumn = 'id';
         $table = 'com_branch_bank_details';
         $fields = '*';
         $bankDetails = $this->common->commonGetById($id, $idColumn, $table, $fields);
         return response()->json(['data' => $bankDetails], 200);
     }


}

