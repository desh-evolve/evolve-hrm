<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class IndustryController extends Controller
{
    private $industry = null;
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view user', ['only' => ['index']]);
        $this->middleware('permission:create user', ['only' => ['create','store']]);
        $this->middleware('permission:update user', ['only' => ['update','edit']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);

        $this->industry = new Industry();
        $this->common = new CommonModel();
    }
   

    public function index()
    {
        $industries = Industry::all(); // Fetch all students
        return view('industry.index', ['industries' => $industries]);
    }
     
    public function create(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'industry_name' => 'required',
                    // 'company_id' => 'required',
                ]);
                $data = [
                    'industry_name' => $request->industry_name,
                    'status' => 'active',
                    'created_date' => date("Y-m-d H:i:s"),
                    'created_by' => Auth::user()->id,
                ];
                //store the record in the transaction_classes table
                $insertId = $this->industry->storeRecord($data);

                if ($insertId) {
                    return response()->json(['message' => 'industry  Added Successfully' , 'data' => ['id' => $insertId]], 200);
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
         $student = Industry::findOrFail($id);
         return 'industry.edit';
    }
 
     
    public function update(Request $request, $id)
    {
        $request->validate([
            'industry_name' => 'required',
            // 'company_id' => 'required',
        ]);
        $record = $this->industry->getSingleRecord($id);
        if ($record) {
            $data = [
                'industry_name' => $request->industry_name,
                'status' => $request->status,
                'updated_date' => date("Y-m-d H:i:s"),
                'updated_by' => Auth::user()->id,
            ];
            $this->industry->updateRecord($id, $data);
            return response()->json(['message' => 'Department Updated Successfully',  'data' => ['id' => $id]], 200);
        } else {
            return response()->json(['message' => 'No Department Found', 'data' => []], 404);
        }
    }

    public function show($id)
    {
        $data = $this->industry->getSingleRecord($id);
        if ($data) {
            return response()->json(['industries' => $data[0]], 200);
        } else {
            return response()->json(['message' => 'No industry Found'], 404);
        }
    }

    public function delete($id)
    {
        $record = $this->industry->getSingleRecord($id);
        if ($record) {
            $data = [
                'deleted_date' => date("Y-m-d H:i:s"),
                'deleted_by' => Auth::user()->id,
                'status'          => 'delete',
            ];
            $this->industry->destroyRecord($id, $data);
            return response()->json(['message' => 'Department Deleted Successfully',  'data' => ['id' => $id]], 200);
        } else {
            return response()->json(['message' => 'No Department Found', 'data' => []], 404);
        }
    }
    
}
