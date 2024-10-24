<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class StationController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view station', ['only' => [
            'index', 
            'getAllCountries',
            'getAllStations',
            'getStationByStationId',
            'getAllStationTypes',
            'getStationTypeById',
        ]]);
        $this->middleware('permission:create station', ['only' => ['createStation', 'createStationType']]);
        $this->middleware('permission:update station', ['only' => ['updateStation', 'updateStationType']]);
        $this->middleware('permission:delete station', ['only' => ['deleteStation', 'deleteStationType']]);

        $this->common = new CommonModel();
    }

// ==================================================================
// Station / 2024/10/22 (manori)
// ==================================================================
    public function index()
    {
        return view('station.index');
    }

    public function createStation(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'branch_id' => 'required',
                    'station_type_id' => 'required',
                ]);

                $table = 'com_stations';
                $inputArr = [
                    'branch_id' => $request->branch_id,
                    'station_type_id' => $request->station_type_id,
                    'station_customer_id' => $request->station_customer_id,
                    'source' => $request->source,
                    'description' => $request->description, 
                    'time_zone' => $request->time_zone, 
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Station added successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Station', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function updateStation(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'branch_id' => 'required',
                    'station_type_id' => 'required',
                ]);

                $table = 'com_stations';
                $idColumn = 'id';
                $inputArr = [
                    'branch_id' => $request->branch_id,
                    'station_type_id' => $request->station_type_id,
                    'station_customer_id' => $request->station_customer_id,
                    'source' => $request->source,
                    'description' => $request->description, 
                    'time_zone' => $request->time_zone, 
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Station updated successfully' , 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Station', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteStation($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Station';
        $table = 'com_stations';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllStations()
    {
        $table = 'com_stations';
        $fields = '*';
        $stations = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $stations], 200);
    }

    public function getStationByStationId($id){
        $idColumn = 'id';
        $table = 'com_stations';
        $fields = '*';
        $stations = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $stations], 200);
    }
// ==================================================================
// Station Type / 2024/10/22 (manori)
// ==================================================================
    public function createStationType(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'type_name' => 'required',
                ]);

                $table = 'com_station_types';
                $inputArr = [
                    'type_name' => $request->type_name,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Station Type added successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Station Type', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }


    public function updateStationType(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'type_name' => 'required',
                ]);

                $table = 'com_station_types';
                $idColumn = 'id';
                $inputArr = [
                    'type_name' => $request->type_name,
                    'country_id' => $request->country_id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    return response()->json(['status' => 'success', 'message' => 'Station Type updated successfully', 'data' => ['id' => $insertId]], 200);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed updating Station Type', 'data' => []], 500);
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    public function deleteStationType($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Station Types';
        $table = 'com_station_types';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllStationTypes()
    {
        $table = 'com_station_types';
        $fields = '*';
        $stations_type = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $stations_type], 200);
    }

    public function getStationTypeById($id){
        $idColumn = 'id';
        $table = 'com_station_types';
        $fields = '*';
        $stations_type = $this->common->commonGetById($id, $idColumn, $table, $fields);
        return response()->json(['data' => $stations_type], 200);
    }
}
