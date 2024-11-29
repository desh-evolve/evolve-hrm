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
                // $request->validate([
                //     'employee_id' => 'required',
                //     'company' => 'required',
                //     'from_date' => 'required',
                //     'to_date' => 'required',
                //     'department' => 'required',
                //     'designation' => 'required',
                //     'remarks' => 'required',
                // ]);

                $table = 'emp_work_experience';
                $inputArr = [
                    'employee_id' => $request->employee_id,
                    'company' => $request->company,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'department' => $request->department,
                    'designation' => $request->designation,
                    'remarks' => $request->remarks,
                    'status' => $request->work_experience_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Work Experience  added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Work Experience', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updatePayStubAccount(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'employee_id' => 'required',
                    'company' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    'department' => 'required',
                    'designation' => 'required',
                    'remarks' => 'required',
                ]);

                $table = 'emp_work_experience';
                $idColumn = 'id';
                $inputArr = [
                    'employee_id' => $request->employee_id,
                    'company' => $request->company,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                    'department' => $request->department,
                    'designation' => $request->designation,
                    'remarks' => $request->remarks,
                    'status' => $request->work_experience_status,
                    'updated_by' => Auth::user()->id,

                ];
                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Work Experience updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Work Experience', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deletePayStubAccount($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Employee Work Experience';
        $table = 'emp_work_experience';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllPayStubAccountController()
    {
        $table = 'emp_work_experience';
        $fields = '*';
        $employee_work_experience = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $employee_work_experience], 200);
    }

    public function getPayStubAccountById($id)
    {
        $idColumn = 'id';
        $table = 'emp_work_experience';
        $fields = '*';
        $employee_work_experience = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $employee_work_experience], 200);
    }
}
