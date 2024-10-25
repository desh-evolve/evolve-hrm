<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class BranchController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view branch', ['only' => [
            'index', 
            'getAllBranches', 
            'getBranchByBranchId', 
            'getBranchDropdownData'
        ]]);
        $this->middleware('permission:create branch', ['only' => ['createBranch']]);
        $this->middleware('permission:update branch', ['only' => ['updateBranch']]);
        $this->middleware('permission:delete branch', ['only' => ['deleteBranch']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.branch.index');
    }

    //desh(2024-10-22)
    public function getBranchDropdownData(){
        $countries = $this->common->commonGetAll('loc_countries', '*');
        $provinces = $this->common->commonGetAll('loc_provinces', '*');
        $cities = $this->common->commonGetAll('loc_cities', '*');
        $currencies = $this->common->commonGetAll('com_currencies', '*');
        return response()->json([
            'data' => [
                'countries' => $countries,
                'provinces' => $provinces,
                'cities' => $cities,
                'currencies' => $currencies,
            ]
        ], 200);
    }

    //================================================================================================================================

    //desh(2024-10-21)
    public function createBranch(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'branch_name' => 'required|string|max:255',
                    'short_name' => 'nullable|string|max:100',
                    'address_1' => 'required|string|max:255',
                    'city_id' => 'required|integer',
                    'province_id' => 'required|integer',
                    'country_id' => 'required|integer',
                    'contact_1' => 'required|string|max:15',
                    'email' => 'required|email|unique:com_branches,email',
                    'branch_status' => 'required|string',
                ]);
    
                $table = 'com_branches';
                $inputArr = [
                    'company_id' => 1, //hard coded - change later - check here
                    'branch_name' => $request->branch_name,
                    'short_name' => $request->short_name,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city_id' => $request->city_id,
                    'province_id' => $request->province_id,
                    'country_id' => $request->country_id,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'email' => $request->email,
                    'status' => $request->branch_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
    
                $insertId = $this->common->commonSave($table, $inputArr);
    
                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Branch added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add branch', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    //desh(2024-10-21)
    public function updateBranch(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'branch_name' => 'required|string|max:255',
                    'short_name' => 'nullable|string|max:100',
                    'address_1' => 'required|string|max:255',
                    'city_id' => 'required|integer',
                    'province_id' => 'required|integer',
                    'country_id' => 'required|integer',
                    'contact_1' => 'required|string|max:15',
                    'email' => 'required|email|unique:com_branches,email,' . $id,
                    'branch_status' => 'required|string',
                ]);
    
                $table = 'com_branches';
                $idColumn = 'id';
                $inputArr = [
                    'company_id' => 1, //hard coded - change later - check here
                    'branch_name' => $request->branch_name,
                    'short_name' => $request->short_name,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city_id' => $request->city_id,
                    'province_id' => $request->province_id,
                    'country_id' => $request->country_id,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'email' => $request->email,
                    'status' => $request->branch_status,
                    'updated_by' => Auth::user()->id,
                ];
    
                $updatedId = $this->common->commonSave($table, $inputArr, $id, $idColumn);
    
                if ($updatedId) {
                    return response()->json(['status' => 'success', 'message' => 'Branch updated successfully', 'data' => ['id' => $updatedId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update branch', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    //desh(2024-10-21)
    public function deleteBranch($id)
    {
        $res = $this->common->commonDelete($id, ['id' => $id], 'Branch', 'com_branches');
        $this->common->commonDelete($id, ['branch_id' => $id], 'Department Branches', 'com_branch_departments');
        $this->common->commonDelete($id, ['branch_id' => $id], 'Department Branches Employees', 'com_branch_department_employees');
        return $res;
    }
    
    //desh(2024-10-21)
    public function getAllBranches()
    {
        $table = 'com_branches';
        $fields = [
            'com_branches.*', 'com_branches.id as id', 'com_branches.status as status',
            'loc_countries.country_name', 'loc_provinces.province_name', 'loc_cities.city_name', 
            'com_currencies.currency_name', 'com_currencies.iso_code'
        ];
        $joinsArr = [
            'loc_countries' => ['loc_countries.id', '=', 'com_branches.country_id'],
            'loc_provinces' => ['loc_provinces.id', '=', 'com_branches.province_id'],
            'loc_cities' => ['loc_cities.id', '=', 'com_branches.city_id'],
            'com_currencies' => ['com_currencies.id', '=', 'com_branches.currency_id']
        ];
        $branches = $this->common->commonGetAll($table, $fields, $joinsArr, $whereArr = [], $exceptDel = true);
        return response()->json(['data' => $branches], 200);
    }
    
    //desh(2024-10-21)
    public function getBranchByBranchId($id)
    {
        $idColumn = 'id';
        $table = 'com_branches';
        $fields = '*';
        $branch = $this->common->commonGetById($id, $idColumn, $table, $fields, [], [], true);
        return response()->json(['data' => $branch], 200);
    }       

}