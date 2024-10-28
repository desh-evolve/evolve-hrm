<?php

namespace App\Http\Controllers\Company;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WageGroupController extends Controller
{

    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view wage groups', ['only' => ['wageGroup', 'getAllWageGroups', 'getWageGroupById']]);
        $this->middleware('permission:create wage groups', ['only' => ['createWageGroups']]);
        $this->middleware('permission:update wage groups', ['only' => ['updateWageGroups']]);
        $this->middleware('permission:delete wage groups', ['only' => ['deleteWageGroups']]);


        $this->common = new CommonModel();
    }


    //pawanee(2024-10-21)
    public function wageGroup(){
        return view('company.wageGroups.wageGroups_add');
    }


    //pawanee(2024-10-21)
    public function createWageGroups(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'wage_group_name' => 'required',
                ]);

                $table = 'com_wage_groups';
                $inputArr = [
                    'wage_group_name' => $request->wage_group_name,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'WageGroup Added Successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to Add WageGroup', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }



    //pawanee(2024-10-21)
    public function updateWageGroups(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'wage_group_name' => 'required',

                ]);

                $table = 'com_wage_groups';
                $idColumn = 'id';
                $inputArr = [
                    'wage_group_name' => $request->wage_group_name,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'WageGroup updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating WageGroup', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }



    //pawanee(2024-10-22)
    public function deleteWageGroups($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Wage Group';
        $table = 'com_wage_groups';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }


    //pawanee(2024-10-22)
    public function getAllWageGroups()
    {
        $table = 'com_wage_groups';
        $fields = '*';
        $wageGroups = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $wageGroups], 200);
    }


    //pawanee(2024-10-22)
    public function getWageGroupById($id){
        $idColumn = 'id';
        $table = 'com_wage_groups';
        $fields = '*';
        $wageGroups = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $wageGroups], 200);
    }

}
