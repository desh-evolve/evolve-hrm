<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class PayStubEntryAccountLinkController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view pay stub entry account link', ['only' => [
            'index',
            'getAllPayStubEntryAccountLink',
            'getPayStubEntryAccountLinkById',
        ]]);
        $this->middleware('permission:create pay stub entry account link', ['only' => ['createPayStubEntryAccountLink']]);
        $this->middleware('permission:update pay stub entry account link', ['only' => ['updatePayStubEntryAccountLink']]);
        $this->middleware('permission:delete pay stub entry account link', ['only' => ['deletePayStubEntryAccountLink']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        // var_dump('start 2');
        return view('payroll.pay_stub_entry_account_link.index');
    }
    public function getPayStubEntryAccountLinkDropdownData()
    {
        $pay_stub_entry_accounts = DB::table('pay_stub_entry_account')
        ->select(
            'id',
            DB::raw("CONCAT(UPPER(LEFT(`type`, 1)), LOWER(SUBSTRING(`type`, 2)), ' - ', `name`) AS name")
        )
        ->where('status', 'active')
        ->get();

        //type => create table
        $type = [
            ['id' => 1, 'name' => 'Total - Total Gross', 'value' => 'gross'],
            ['id' => 2, 'name' => 'Total - Total Deduction', 'value' => 'deduction'],
            ['id' => 3, 'name' => 'Total - Net Pay', 'value' => 'net_pay'],
            ['id' => 4, 'name' => 'Total - Net Income (Rs)', 'value' => 'net_income'],
        ];

        return response()->json([
            'data' => [
                'pay_stub_entry_accounts' => $pay_stub_entry_accounts,
                'type' => $type,
            ]
        ], 200);
    }

    public function createPayStubEntryAccountLink(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'total_gross' => 'string',
                    'total_employee_deduction' => 'string',
                    'total_employer_deduction' => 'string',
                    'regular_time' => 'string',
                ]);

                $table = 'pay_stub_entry_account_link';
                $inputArr = [
                    'company_id' => 1,
                    'total_gross' => $request->total_gross,
                    'total_employee_deduction' => $request->total_employee_deduction,
                    'total_employer_deduction' => $request->total_employer_deduction,
                    'total_net_pay' => $request->total_net_pay,
                    'regular_time' => $request->regular_time,
                    'monthly_advance' => $request->monthly_advance,
                    'monthly_advance_deduction' => $request->monthly_advance_deduction,
                    'employee_cpp' => $request->employee_cpp,
                    'employee_ei' => $request->employee_ei,
                    'status' => $request->pay_stub_entry_account_link_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Pay Stub Entry Account Link added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Pay Stub Entry Account Link', 'data' => []], 500);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updatePayStubEntryAccountLink(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'total_gross' => 'string',
                    'total_employee_deduction' => 'string',
                    'total_employer_deduction' => 'string',
                    'regular_time' => 'string',
                ]);

                $table = 'pay_stub_entry_account_link';
                $idColumn = 'id';
                $inputArr = [
                    'company_id' => 1,
                    'total_gross' => $request->total_gross,
                    'total_employee_deduction' => $request->total_employee_deduction,
                    'total_employer_deduction' => $request->total_employer_deduction,
                    'total_net_pay' => $request->total_net_pay,
                    'regular_time' => $request->regular_time,
                    'monthly_advance' => $request->monthly_advance,
                    'monthly_advance_deduction' => $request->monthly_advance_deduction,
                    'employee_cpp' => $request->employee_cpp,
                    'employee_ei' => $request->employee_ei,
                    'status' => $request->pay_stub_entry_account_link_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Pay Stub Entry Account Link updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Pay Stub Entry Account Link', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deletePayStubEntryAccountLink($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Pay Stub Entry Account Link';
        $table = 'pay_stub_entry_account_link';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllPayStubEntryAccountLink()
    {
        $table = 'pay_stub_entry_account_link';
        $fields = '*';
        $pay_stub_entry_account_link = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $pay_stub_entry_account_link], 200);
    }

    public function getPayStubEntryAccountLinkById($id)
    {
        $idColumn = 'id';
        $table = 'pay_stub_entry_account_link';
        $fields = '*';
        $pay_stub_entry_account_link = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $pay_stub_entry_account_link], 200);
    }
}
