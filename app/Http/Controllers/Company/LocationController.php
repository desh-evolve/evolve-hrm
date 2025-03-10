<?php

namespace App\Http\Controllers\Company;

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
        $this->middleware('permission:view location', ['only' => [
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
        $this->middleware('permission:create location', ['only' => ['createCountry', 'createProvince', 'createCity']]);
        $this->middleware('permission:update location', ['only' => ['updateCountry', 'updateProvince', 'updateCity']]);
        $this->middleware('permission:delete location', ['only' => ['deleteCountry', 'deleteProvince', 'deleteCity']]);

        $this->common = new CommonModel();
    }

    //desh(2024-10-18)
    public function index()
    {
        return view('location.index');
    }

    //================================================================================================================================
    // country
    //================================================================================================================================

    //desh(2024-10-18)
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

    //desh(2024-10-18)
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

    //desh(2024-10-18)
    public function deleteCountry($id)
    {
        $res =  $this->common->commonDelete($id, ['id' => $id], 'Country', 'loc_countries');
        $this->common->commonDelete($id, ['country_id' => $id], 'Province', 'loc_provinces');
        $this->common->commonDelete($id, ['province_id' => $id], 'City', 'loc_cities');

        return $res;
    }


    //desh(2024-10-18)
    public function getAllCountries()
    {
        $table = 'loc_countries';
        $fields = '*';
        $countries = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $countries], 200);
    }

    //desh(2024-10-18)
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

    //desh(2024-10-18)
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

    //desh(2024-10-18)
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

    //desh(2024-10-18)
    public function deleteProvince($id)
    {
        $res = $this->common->commonDelete($id, ['id' => $id], 'Province', 'loc_provinces');
        $this->common->commonDelete($id, ['province_id' => $id], 'City', 'loc_cities');

        return $res;
    }

    
    //desh(2024-10-18)
    public function getAllProvinces()
    {
        $table = 'loc_provinces';
        $fields = ['loc_provinces.*', 'loc_provinces.id as id', 'loc_countries.country_name'];
        $joinsArr = ['loc_countries' => ['loc_countries.id', '=', 'loc_provinces.country_id']];
        $whereArr = ['loc_countries.status' => 'active'];
        $provinces = $this->common->commonGetAll($table, $fields, $joinsArr, $whereArr);
        return response()->json(['data' => $provinces], 200);
    }

    //desh(2024-10-18)
    public function getProvinceByProvinceId($id){
        $idColumn = 'id';
        $table = 'loc_provinces';
        $fields = '*';
        $provinces = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $provinces], 200);
    }

    //desh(2024-10-18)
    public function getProvincesByCountryId($id){
        $idColumn = 'country_id';
        $table = 'loc_provinces';
        $fields = '*';
        $provinces = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $provinces], 200);
    }

    //================================================================================================================================
    // city
    //================================================================================================================================

    //desh(2024-10-18)
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

    //desh(2024-10-18)
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

    //desh(2024-10-18)
    public function deleteCity($id)
    {
        $whereArr = ['id' => $id];
        $title = 'City';
        $table = 'loc_cities';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    //desh(2024-10-18)
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

    //desh(2024-10-18)
    public function getCityByCityId($id){
        $idColumn = 'id';
        $table = 'loc_cities';
        $fields = '*';
        $cities = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $cities], 200);
    }

    //desh(2024-10-18)
    public function getCitiesByProvinceId($id){
        $idColumn = 'province_id';
        $table = 'loc_cities';
        $fields = '*';
        $cities = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $cities], 200);
    }


}
