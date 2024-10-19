<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class LocationController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view locations', ['only' => [
            'index', 
            'getAllCountries', 
            'getCountryByCountryId', 
            'getAllProvinces',
            'getProvinceByProvinceId', 
            'getProvincesByCountryId', 
            'getAllCities',
            'getCityByCityId', 
            'getCitiesByProvinceId'
        ]]);
        $this->middleware('permission:create locations', ['only' => ['createCountry', 'createProvince', 'createCity']]);
        $this->middleware('permission:update locations', ['only' => ['updateCountry', 'updateProvince', 'updateCity']]);
        $this->middleware('permission:delete locations', ['only' => ['deleteCountry', 'deleteProvince', 'deleteCity']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('location.index');
    }

    //================================================================================================================================
    // country
    //================================================================================================================================
    public function createCountry(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'country_name' => 'required',
                    'country_code' => 'required',
                ]);

                $table = 'loc_countries';
                $inputArr = [
                    'country_name' => $request->country_name,
                    'country_code' => $request->country_code,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Country  added successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Country', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
 
    public function updateCountry(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'country_name' => 'required',
                    'country_code' => 'required',
                ]);

                $table = 'loc_countries';
                $idColumn = 'id';
                $inputArr = [
                    'country_name' => $request->country_name,
                    'country_code' => $request->country_code,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Country updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Country', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteCountry($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Country';
        $table = 'loc_countries';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllCountries()
    {
        $table = 'loc_countries';
        $fields = '*';
        $countries = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $countries], 200);
    }

    public function getCountryByCountryId($id){
        $idColumn = 'id';
        $table = 'loc_countries';
        $fields = '*';
        $countries = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $countries], 200);
    }

    //================================================================================================================================
    // province
    //================================================================================================================================
    public function createProvince(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'province_name' => 'required',
                    'country_id' => 'required',
                ]);

                $table = 'loc_provinces';
                $inputArr = [
                    'province_name' => $request->province_name,
                    'country_id' => $request->country_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                
                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Province added successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding province', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }
    
    public function updateProvince(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'province_name' => 'required',
                    'country_id' => 'required',
                ]);

                $table = 'loc_provinces';
                $idColumn = 'id';
                $inputArr = [
                    'province_name' => $request->province_name,
                    'country_id' => $request->country_id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Province updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating province', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteProvince($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Province';
        $table = 'loc_provinces';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllProvinces()
    {
        $table = 'loc_provinces';
        $fields = ['loc_provinces.*', 'loc_provinces.id as id', 'loc_countries.country_name'];
        $joinsArr = ['loc_countries' => ['loc_countries.id', '=', 'loc_provinces.country_id']];
        $whereArr = ['loc_countries.status' => 'active'];
        $provinces = $this->common->commonGetAll($table, $fields, $joinsArr, $whereArr);
        return response()->json(['data' => $provinces], 200);
    }

    public function getProvinceByProvinceId($id){
        $idColumn = 'id';
        $table = 'loc_provinces';
        $fields = '*';
        $provinces = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $provinces], 200);
    }

    public function getProvincesByCountryId($id){}

    //================================================================================================================================
    // city
    //================================================================================================================================
    public function createCity(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'city_name' => 'required',
                    'province_id' => 'required',
                ]);

                $table = 'loc_cities';
                $inputArr = [
                    'city_name' => $request->city_name,
                    'province_id' => $request->province_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                
                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'City  added successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding city', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateCity(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'city_name' => 'required',
                    'province_id' => 'required',
                ]);

                $table = 'loc_cities';
                $idColumn = 'id';
                $inputArr = [
                    'city_name' => $request->city_name,
                    'province_id' => $request->province_id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'City updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating city', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteCity($id)
    {
        $whereArr = ['id' => $id];
        $title = 'City';
        $table = 'loc_cities';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllCities()
    {
        $table = 'loc_cities';
        $fields = ['loc_cities.*', 'loc_cities.id as id', 'loc_provinces.province_name'];
        $joinsArr = [
            'loc_provinces' => ['loc_provinces.id', '=', 'loc_cities.province_id'],
            'loc_countries' => ['loc_countries.id', '=', 'loc_provinces.country_id'],
        ];
        $whereArr = [
            'loc_provinces.status' => 'active',
            'loc_countries.status' => 'active'
        ];
        $cities = $this->common->commonGetAll($table, $fields, $joinsArr, $whereArr);
        return response()->json(['data' => $cities], 200);
    }

    public function getCityByCityId($id){
        $idColumn = 'id';
        $table = 'loc_cities';
        $fields = '*';
        $cities = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $cities], 200);
    }

    public function getCitiesByProvinceId($id){}


}
