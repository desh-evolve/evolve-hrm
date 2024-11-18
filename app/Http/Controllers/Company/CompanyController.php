<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class CompanyController extends Controller
{
    private $company = null;
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view company', ['only' => ['index', 'getCompanyByCompanyId']]);
        $this->middleware('permission:update company', ['only' => ['updateCompany']]);

        $this->common = new CommonModel();
    }

    //desh(2024-10-21)
    public function index()
    {
        return view('company.company_info');
    }

    //desh(2024-10-21)
    public function updateCompany(Request $request, $id)
    {
        // var_dump('submit');
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'company_name' => 'required|string|max:255',
                    'company_short_name' => 'nullable|string|max:255',
                    'industry_id' => 'nullable|integer',
                    'business_reg_no' => 'nullable|string|max:50',
                    'address_1' => 'nullable|string',
                    'address_2' => 'nullable|string',
                    'city_id' => 'nullable|integer',
                    'province_id' => 'nullable|integer',
                    'country_id' => 'nullable|integer',
                    'postal_code' => 'nullable|string|max:10',
                    'contact_1' => 'nullable|string|max:15',
                    'contact_2' => 'nullable|string|max:15',
                    'email' => 'nullable|string|email',
                    'epf_reg_no' => 'nullable|string|max:50',
                    'tin_no' => 'nullable|string|max:50',
                    'admin_contact_id' => 'nullable|integer',
                    'billing_contact_id' => 'nullable|integer',
                    'primary_contact_id' => 'nullable|integer',
                    // 'logo' => 'nullable|string',
                    // 'logo_small' => 'nullable|string',
                    'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate logo image
                    'logo_small_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate small logo image
                    'website' => 'nullable|string',
                    'status' => 'nullable|string',
                ]);

                $table = 'com_companies';
                $idColumn = 'id';
                $inputArr = [
                    'company_name' => $request->company_name,
                    'company_short_name' => $request->company_short_name,
                    'industry_id' => $request->industry_id,
                    'business_reg_no' => $request->business_reg_no,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city_id' => $request->city_id,
                    'province_id' => $request->province_id,
                    'country_id' => $request->country_id,
                    'postal_code' => $request->postal_code,
                    'contact_1' => $request->contact_1,
                    'contact_2' => $request->contact_2,
                    'email' => $request->email,
                    'epf_reg_no' => $request->epf_reg_no,
                    'tin_no' => $request->tin_no,
                    'admin_contact_id' => $request->admin_contact_id,
                    'billing_contact_id' => $request->billing_contact_id,
                    'primary_contact_id' => $request->primary_contact_id,
                    // 'logo' => $request->company_logo,
                    // 'logo_small' => $request->company_logo_small,
                    'website' => $request->website,
                    'status' => $request->status ?? 'active',
                    'updated_by' => Auth::user()->id,
                ];
                if ($request->hasFile('company_logo')) {
                    $logoPath = $request->file('company_logo')->store('logos', 'public'); // Store in 'storage/app/public/logos'
                    $inputArr['logo'] = $logoPath; // Save the path to the database
                }

                // Handle small logo image upload
                if ($request->hasFile('company_logo_small')) {
                    $logoSmallPath = $request->file('company_logo_small')->store('logos/small', 'public'); // Store in 'storage/app/public/logos/small'
                    $inputArr['logo_small'] = $logoSmallPath; // Save the path to the database
                }

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Company updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Company', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    //desh(2024-10-21)
    public function getCompanyByCompanyId($id)
    {
        try {
            $idColumn = 'id';
            $table = 'com_companies';
            $fields = '*'; // Fetch all fields
            $company = $this->common->commonGetById($id, $idColumn, $table, $fields);

            if ($company) {
                return response()->json(['status' => 'success', 'data' => $company], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Company not found', 'data' => []], 404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage(), 'data' => []], 500);
        }
    }
}
