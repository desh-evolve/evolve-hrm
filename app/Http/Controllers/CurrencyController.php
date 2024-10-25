<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;


class CurrencyController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view currency', ['only' => ['currency', 'getAllCurrency', 'getCurrencyById']]);
        $this->middleware('permission:create currency', ['only' => ['createCurrency']]);
        $this->middleware('permission:update currency', ['only' => ['updateCurrency']]);
        $this->middleware('permission:delete currency', ['only' => ['deleteCurrency']]);

        $this->common = new CommonModel();
    }


     //pawanee(2024-10-24)
     public function currency(){
        return view('company.currencies.currencies_add');
     }


      //pawanee(2024-10-24)
      public function createCurrency(Request $request){
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'currency_name' => 'required|string|max:255',
                    'iso_code' => 'required',
                    'conversion_rate' => 'required|numeric',
                    'previous_rate' => 'required|numeric',
                    'is_default' => 'required|boolean',
                ]);

                $table = 'com_currencies';

                 // If the new currency is-default, reset all others to non-default
                if ($request->is_default == 1) {
                    DB::table($table)->update(['is_default' => 0]);
                }


                $inputArr = [
                    'currency_name' => $request->currency_name,
                    'iso_code' => $request->iso_code,
                    'conversion_rate' => $request->conversion_rate,
                    'previous_rate' => $request->previous_rate,
                    'is_default' => $request->is_default,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];


                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Currency Added Successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to Adding Currency', 'data' => []], 500);
                }

            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
      }

      //pawanee(2024-10-24)
      public function updateCurrency(Request $request, $id){
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'currency_name' => 'required|string|max:255',
                    'iso_code' => 'required',
                    'conversion_rate' => 'required|numeric',
                    'previous_rate' => 'required|numeric',
                    'is_default' => 'required|boolean',
                ]);

                $table = 'com_currencies';
                $idColumn = 'id';

                // If updating to be the default, reset all others to non-default
                if ($request->is_default == 1) {
                    DB::table($table)->where('id', '!=', $id)->update(['is_default' => 0]);
                }


                $inputArr = [
                    'currency_name' => $request->currency_name,
                    'iso_code' => $request->iso_code,
                    'conversion_rate' => $request->conversion_rate,
                    'previous_rate' => $request->previous_rate,
                    'is_default' => $request->is_default,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Currency Updated Auccessfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to Update Currency', 'data' => []], 500);
                }

            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
      }



    //pawanee(2024-10-24)
    public function deleteCurrency($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Currency';
        $table = 'com_currencies';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }


    //pawanee(2024-10-24)
    public function getAllCurrency()
    {
        $table = 'com_currencies';
        $fields = '*';
        $wageGroups = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $wageGroups], 200);
    }


    //pawanee(2024-10-24)
    public function getCurrencyById($id){
        $idColumn = 'id';
        $table = 'com_currencies';
        $fields = '*';
        $wageGroups = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $wageGroups], 200);
    }


}
