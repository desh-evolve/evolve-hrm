<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayStubAccountController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view pay stub account', ['only' => [
            'index',
            'getAllPayStubAccount',
            'getPayStubAccountById',
        ]]);
        $this->middleware('permission:create pay stub account', ['only' => ['createPayStubAccount']]);
        $this->middleware('permission:update pay stub account', ['only' => ['updatePayStubAccount']]);
        $this->middleware('permission:delete pay stub account', ['only' => ['deletePayStubAccount']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('payroll.pay_stub_account.index');
    }

    public function createPayStubAccount(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'type' => 'required|string',
                    'name' => 'required|string',
                    'ps_order' => 'required|integer',
                    // 'active' => 'required|string',
                    // 'debit_account' => 'nullable|string',
                    // 'credit_account' => 'nullable|string',
                ]);

                $table = 'pay_stub_entry_account';
                $inputArr = [
                    'company_id' => 1,
                    'active' => $request->active,
                    'type' => $request->type,
                    'name' => $request->name,
                    'ps_order' => $request->ps_order,
                    'accrual_pay_stub_entry_account_id' => $request->accrual_pay_stub_entry_account_id,
                    'debit_account' => $request->debit_account ?: "",
                    'credit_account' => $request->credit_account ?: "",
                    'status' => $request->pay_stub_account_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Account added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Account', 'data' => []], 500);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updatePayStubAccount(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'type' => 'required|string',
                    'name' => 'required|string',
                    'ps_order' => 'required|integer',
                ]);

                $table = 'pay_stub_entry_account';
                $idColumn = 'id';
                $inputArr = [
                    'company_id' => 1,
                    'active' => $request->active,
                    'type' => $request->type,
                    'name' => $request->name,
                    'ps_order' => $request->ps_order,
                    'accrual_pay_stub_entry_account_id' => $request->accrual_pay_stub_entry_account_id,
                    'debit_account' => $request->debit_account ?: "",
                    'credit_account' => $request->credit_account ?: "",
                    'status' => $request->pay_stub_account_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Account updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Account', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deletePayStubAccount($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Pay Stub Account';
        $table = 'pay_stub_entry_account';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllPayStubAccount()
    {
        $table = 'pay_stub_entry_account';
        $fields = '*';
        $pay_stub_account = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $pay_stub_account], 200);
    }

    public function getPayStubAccountById($id)
    {
        $idColumn = 'id';
        $table = 'pay_stub_entry_account';
        $fields = '*';
        $pay_stub_account = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $pay_stub_account], 200);
    }
}
