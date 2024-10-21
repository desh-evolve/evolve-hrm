<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class CurrencyController extends Controller
{
    private $currency = null;
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view user', ['only' => ['index']]);
        $this->middleware('permission:create user', ['only' => ['create','store']]);
        $this->middleware('permission:update user', ['only' => ['update','edit']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);

        $this->currency = new Currency();
        $this->common = new CommonModel();
    }

    public function index()
    {
        $countries = currency::all(); // Fetch all students
        return view('countries.index', ['countries' => $countries]);
    }
    public function create(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'currency_name' => 'required',
                    'conversion_rate' => 'required',
                ]);
                $data = [
                    'currency_name' => $request->currency_name,
                    'iso_code' => $request->iso_code,
                    'conversion_rate' => $request->conversion_rate,
                    'previous_rate' => $request->previous_rate,
                    'status' => 'active',
                    'is_default' => 'is_default',
                    'created_date' => date("Y-m-d H:i:s"),
                    'created_by' => Auth::user()->id,
                ];
                //store the record in the transaction_classes table
                $insertId = $this->currency->storeRecord($data);

                if ($insertId) {
                    return response()->json(['message' => 'currency  Added Successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['message' => 'Error storing record', 'data' => [], 'error_code' => 500], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Error occurred due to ' . $e->getMessage(), 'error_code' => 500], 500);
        }
    }

    public function edit($id)
    {
         $student = Currency::findOrFail($id);
         return 'countries.edit';
    }
 
     
    public function update(Request $request, $id)
    {
        $request->validate([
            'currency_name' => 'required',
            'conversion_rate' => 'required',
        ]);
        $record = $this->currency->getSingleRecord($id);
        if ($record) {
            $data = [
                'currency_name' => $request->currency_name,
                'iso_code' => $request->iso_code,
                'conversion_rate' => $request->conversion_rate,
                'previous_rate' => $request->previous_rate,
                'status' => 'active',
                'is_default' => 'is_default',
                'updated_date' => date("Y-m-d H:i:s"),
                'updated_by' => Auth::user()->id,
            ];
            $this->currency->updateRecord($id, $data);
            return response()->json(['message' => 'currency Updated Successfully',  'data' => ['id' => $id]], 200);
        } else {
            return response()->json(['message' => 'No Department Found', 'data' => []], 404);
        }
    }

    public function show($id)
    {
        $data = $this->currency->getSingleRecord($id);
        if ($data) {
            return response()->json(['currency' => $data[0]], 200);
        } else {
            return response()->json(['message' => 'No industry Found'], 404);
        }
    }

    public function delete($id)
    {
        $record = $this->currency->getSingleRecord($id);
        if ($record) {
            $data = [
                'deleted_date' => date("Y-m-d H:i:s"),
                'deleted_by' => Auth::user()->id,
                'status'          => 'delete',
            ];
            $this->currency->destroyRecord($id, $data);
            return response()->json(['message' => 'currency Deleted Successfully',  'data' => ['id' => $id]], 200);
        } else {
            return response()->json(['message' => 'No currency Found', 'data' => []], 404);
        }
    }
}
