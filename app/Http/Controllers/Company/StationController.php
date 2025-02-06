<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
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
            'getDropdownList',
            'getAllStation',
            'getStationById',
        ]]);
        $this->middleware('permission:create station', ['only' => ['createStation']]);
        $this->middleware('permission:update station', ['only' => ['updateStation']]);
        $this->middleware('permission:delete station', ['only' => ['deleteStation']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.station.index');
    }

    public function form()
    {
        return view('company.station.form');
    }

    public function getDropdownList()
    {
        $branches = $this->common->commonGetAll('com_branches', ['id', 'branch_name AS name']);

        // Define connections to other tables
        $connections = [
            'com_branch_departments' => [
                'con_fields' => ['branch_id', 'department_id', 'branch_name'],  // Fields to select from connected table
                'con_where' => ['com_branch_departments.department_id' => 'id'],  // Link to the main table (department_id)
                'con_joins' => [
                    'com_branches' => ['com_branches.id', '=', 'com_branch_departments.branch_id'],
                ],
                'con_name' => 'branch_departments',  // Alias to store connected data in the result
                'except_deleted' => false,  // Filter out soft-deleted records
            ],
        ];

        // Fetch the department with connections
        $departments = $this->common->commonGetAll('com_departments', ['id', 'department_name AS name'], [], [], false, $connections);
        $user_groups = $this->common->commonGetAll('com_employee_groups', ['id', 'emp_group_name AS name']);
        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        return response()->json([
            'data' => [
                'branches' => $branches,
                'departments' => $departments,
                'user_groups' => $user_groups,
                'users' => $users,
            ]
        ], 200);
    }

    public function createStation(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'branch_id' => 'required',
                    'type' => 'required',
                    'branch_ids' => 'nullable|json',
                    'department_ids' => 'nullable|json',
                    'include_user_ids' => 'nullable|json',
                    'exclude_user_ids' => 'nullable|json',
                    'group_ids' => 'nullable|json',
                ]);

                $table = 'com_stations';
                $inputArr = [
                    'branch_id' => $request->branch_id,
                    'department_id' => $request->department_id,
                    'station_type_id' => $request->type,
                    'station_customer_id' => $request->station,
                    'source' => $request->source,
                    'description' => $request->description,
                    'status' => $request->station_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr);

                if ($insertId) {
                    $this->saveOtherStationTableDetails($request, $insertId);
                    $this->saveStationUserDetails($request, $insertId);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Station', 'data' => []], 500);
                }

                return response()->json(['status' => 'success', 'message' => 'Station added successfully', 'data' => ['id' => $insertId]], 200);
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
                    'type' => 'required',
                    'branch_ids' => 'nullable|json',
                    'department_ids' => 'nullable|json',
                    'include_user_ids' => 'nullable|json',
                    'exclude_user_ids' => 'nullable|json',
                    'group_ids' => 'nullable|json',
                ]);

                $table = 'com_stations';
                $idColumn = 'id';
                $inputArr = [
                    'branch_id' => $request->branch_id,
                    'department_id' => $request->department_id,
                    'station_type_id' => $request->type,
                    'station_customer_id' => $request->station,
                    'source' => $request->source,
                    'description' => $request->description,
                    'status' => $request->station_status,
                    'updated_by' => Auth::user()->id,
                ];

                $insertId = $this->common->commonSave($table, $inputArr, $id, $idColumn);

                if ($insertId) {
                    $this->saveOtherStationTableDetails($request, $id);
                    $this->saveStationUserDetails($request, $id);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed adding Station', 'data' => []], 500);
                }

                return response()->json(['status' => 'success', 'message' => 'Station added successfully', 'data' => ['id' => $insertId]], 200);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred due to ' . $e->getMessage(), 'data' => []], 500);
        }
    }

    private function saveOtherStationTableDetails($request, $station_id)
    {
        // Handle branch_ids
        if (!empty($request->branch_ids)) {
            $branchIds = json_decode($request->branch_ids, true);
            if (!empty($branchIds) && is_array($branchIds)) {
                $insertData = array_map(function ($id) use ($request, $station_id) {
                    $status = $request->station_status ?? 'active';
                    return [
                        'station_id' => $station_id,
                        'branch_id' => $id,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $branchIds);

                DB::table('station_branch')->insert($insertData);
            }
        }

        // Handle department_ids
        if (!empty($request->department_ids)) {
            $departmentIds = json_decode($request->department_ids, true);
            if (!empty($departmentIds) && is_array($departmentIds)) {
                $insertData = array_map(function ($id) use ($request, $station_id) {
                    $status = $request->station_status ?? 'active';
                    return [
                        'station_id' => $station_id,
                        'department_id' => $id,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $departmentIds);

                DB::table('station_department')->insert($insertData);
            }
        }

        // Handle exclude_user_ids
        if (!empty($request->exclude_user_ids)) {
            $excludeUserIds = json_decode($request->exclude_user_ids, true);
            if (!empty($excludeUserIds) && is_array($excludeUserIds)) {
                $insertData = array_map(function ($id) use ($request, $station_id) {
                    $status = $request->station_status ?? 'active';
                    return [
                        'station_id' => $station_id,
                        'user_id' => $id,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $excludeUserIds);

                DB::table('station_exclude_user')->insert($insertData);
            }
        }

        if (!empty($request->include_user_ids)) {
            $includeUserIds = json_decode($request->include_user_ids, true);

            if (is_array($includeUserIds) && !empty($includeUserIds)) {
                $insertData = array_map(function ($id) use ($request, $station_id) {
                    $status = $request->station_status ?? 'active';
                    return [
                        'station_id' => $station_id,
                        'user_id' => $id,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $includeUserIds);

                DB::table('station_include_user')->insert($insertData);
            }
        }

        // Handle group_ids
        if (!empty($request->group_ids)) {
            $groupIds = json_decode($request->group_ids, true);
            if (!empty($groupIds) && is_array($groupIds)) {
                $insertData = array_map(function ($id) use ($request, $station_id) {
                    $status = $request->station_status ?? 'active';
                    return [
                        'station_id' => $station_id,
                        'group_id' => $id,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $groupIds);

                DB::table('station_user_group')->insert($insertData);
            }
        }
    }

    private function saveStationUserDetails($request, $station_id)
    {
        if (!empty($request->include_user_ids)) {
            $userIds = json_decode($request->include_user_ids, true);
            if (!empty($userIds) && is_array($userIds)) {
                $insertData = array_map(function ($id) use ($request, $station_id) {
                    $status = $request->station_status ?? 'active';
                    return [
                        'station_id' => $station_id,
                        'user_id' => $id,
                        'status' => $status,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ];
                }, $userIds);

                DB::table('station_user')->insert($insertData);
            }
        }
    }

    public function deleteStation($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Station';
        $table = 'com_stations';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    public function getAllStation()
    {
        $table = 'com_stations';
        $fields = '*';
        $station = $this->common->commonGetAll($table, $fields);
        return response()->json(['data' => $station], 200);
    }

    public function getStationById($stationId)
    {
        $station = DB::table('com_stations')
            ->leftJoin('station_branch', 'com_stations.id', '=', 'station_branch.station_id')
            ->leftJoin('station_department', 'com_stations.id', '=', 'station_department.station_id')
            ->leftJoin('station_user_group', 'com_stations.id', '=', 'station_user_group.station_id')
            ->leftJoin('station_include_user', 'com_stations.id', '=', 'station_include_user.station_id')
            ->leftJoin('station_exclude_user', 'com_stations.id', '=', 'station_exclude_user.station_id')
            ->select(
                'com_stations.id',
                'com_stations.branch_id',
                'com_stations.department_id',
                'com_stations.station_type_id',
                'com_stations.station_customer_id',
                'com_stations.source',
                'com_stations.description',
                'com_stations.status',
                'com_stations.time_zone',
                DB::raw('GROUP_CONCAT(DISTINCT station_branch.branch_id) as branch_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT station_user_group.group_id) as group_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT station_include_user.user_id) as include_user_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT station_exclude_user.user_id) as exclude_user_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT station_department.department_id) as department_ids')
            )
            ->where('com_stations.id', $stationId)
            ->groupBy(
                'com_stations.id',
                'com_stations.branch_id',
                'com_stations.department_id',
                'com_stations.station_type_id',
                'com_stations.station_customer_id',
                'com_stations.source',
                'com_stations.description',
                'com_stations.status',
                'com_stations.time_zone'
            )
            ->first();

        if (!$station) {
            return response()->json(['message' => 'Station not found'], 404);
        }

        // Convert comma-separated strings to arrays
        $station->branch_ids = explode(',', $station->branch_ids);
        $station->group_ids = explode(',', $station->group_ids);
        $station->include_user_ids = explode(',', $station->include_user_ids);
        $station->exclude_user_ids = explode(',', $station->exclude_user_ids);
        $station->department_ids = explode(',', $station->department_ids);

        // return response()->json($station);
        return response()->json(['data' => $station], 200);
    }
}
