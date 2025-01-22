<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class HierarchyController extends Controller
{
    private $common = null;

    public function __construct()
    {
        $this->middleware('permission:view hierarchy', ['only' => ['index', 'getAllHierarchy']]);
        $this->middleware('permission:create hierarchy', ['only' => ['form', 'getHierarchyDropdownData', 'createHierarchy']]);
        $this->middleware('permission:update hierarchy', ['only' => ['form', 'getHierarchyDropdownData', 'getHierarchyById', 'updateHierarchy']]);
        $this->middleware('permission:delete hierarchy', ['only' => ['deleteHierarchy']]);

        $this->common = new CommonModel();
    }

    public function index()
    {
        return view('company.hierarchy.index');
    }

    public function form()
    {
        return view('company.hierarchy.form');
    }

    public function getHierarchyDropdownData()
    {

        $object_type = $this->common->commonGetAll('object_type', '*');
        $users = $this->common->commonGetAll('emp_employees', ['id', 'full_name AS name']);

        //type => create table
        $type = [
            ['id' => 1, 'name' => 'Tax', 'value' => 'tax'],
            ['id' => 2, 'name' => 'Deduction', 'value' => 'deduction'],
            ['id' => 3, 'name' => 'Other', 'value' => 'other'],
        ];

        return response()->json([
            'data' => [
                'users' => $users,
                'type' => $type,
                'object_type' => $object_type,
            ]
        ], 200);
    }

    public function getAllHierarchy()
    {
        $data = DB::table('hierarchy_control as hc')
            ->select(
                'hc.id as hierarchy_control_id',
                'hc.name as hierarchy_name',
                'hc.description',
                'hc.status',
                DB::raw("(
            SELECT GROUP_CONCAT(ot.name SEPARATOR '\n')
            FROM hierarchy_object_type hot
            INNER JOIN object_type ot ON hot.object_type_id = ot.id
            WHERE hot.hierarchy_control_id = hc.id
        ) as object_types")
            )
            ->where('hc.status', 'active')
            ->get();
        return response()->json(['data' => $data], 200);
    }

    public function getHierarchyById($id)
    {

        $pg = DB::table('hierarchy_control as hc')
            ->select(
                'hc.id as hierarchy_control_id',
                'hc.name as hierarchy_name',
                'hc.description',
                'hc.status',
                DB::raw("(
            SELECT GROUP_CONCAT(ot.id SEPARATOR ',')
            FROM hierarchy_object_type hot
            INNER JOIN object_type ot ON hot.object_type_id = ot.id
            WHERE hot.hierarchy_control_id = hc.id
            ) as object_types_ids")
            )
            ->where('hc.id', $id)  // Filtering by hierarchy_control_id
            ->first();

        // Convert object_types_ids to an array
        $objectTypesIds = $pg->object_types_ids ? explode(',', $pg->object_types_ids) : [];

        // Add objectTypesIds to the response data
        $pg->object_types_ids = $objectTypesIds;

        // dd('data', $pg);
        return response()->json(['data' => $pg], 200);
    }

    public function deleteHierarchy($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Pay Period Schedule';
        $table = 'hierarchy_control';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }

    /**
     * Create a new pay period schedule with associated policies.
     */
    public function createHierarchy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'object_ids' => 'nullable|json',
                    'user_ids' => 'nullable|json',
                ]);

                // dd($request->all());
                $hierarchyInput = [
                    'company_id' => 1, // Replace with dynamic company ID
                    'name' => $request->name,
                    'description' => $request->description,

                    'status' => $request->hierarchy_status,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];

                $hierarchyControlId = $this->common->commonSave('hierarchy_control', $hierarchyInput);

                if (!$hierarchyControlId) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to create Hierarchy'], 500);
                }

                // Save associated policies
                $this->saveHierarchyObjectType($hierarchyControlId, $request);
                $this->saveHierarchyUser($hierarchyControlId, $request);
                $this->saveHierarchyLevel($hierarchyControlId, $request);

                return response()->json(['status' => 'success', 'message' => 'Hierarchy created successfully', 'data' => ['id' => $hierarchyControlId]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing pay period schedule with associated policies.
     */
    public function updateHierarchy(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'object_ids' => 'nullable|json',
                    'user_ids' => 'nullable|json',
                ]);

                $hierarchyInput = [
                    'company_id' => 1, // Replace with dynamic company ID
                    'name' => $request->name,
                    'description' => $request->description,

                    'status' => $request->hierarchy_status,
                    'updated_by' => Auth::user()->id,
                ];

                // Update the `pay_period_schedule` table
                $updated = $this->common->commonSave('hierarchy_control', $hierarchyInput, $id, 'id');

                if (!$updated) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to update pay period schedule'], 500);
                }

                $this->saveHierarchyObjectType($id, $request);
                $this->saveHierarchyUser($id, $request);
                $this->saveHierarchyLevel($id, $request);

                return response()->json(['status' => 'success', 'message' => 'Hierarchy updated successfully', 'data' => ['id' => $id]], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Save associated policies for a pay period schedule.
     *
     * @param int $hierarchyControlId
     * @param Request $request
     */

    private function saveHierarchyUser($hierarchyControlId, $request)
    {
        if (!empty($request->user_ids)) {
            $empIds = json_decode($request->user_ids, true);

            if (is_array($empIds)) {
                // Delete all existing users for this pay period schedule
                DB::table('hierarchy_user')
                    ->where('hierarchy_control_id', $hierarchyControlId)
                    ->whereIn('user_id', $empIds)
                    ->delete();

                // Prepare bulk insert data
                $insertData = array_map(function ($empId) use ($hierarchyControlId) {

                    return [
                        'hierarchy_control_id' => $hierarchyControlId,
                        'user_id' => $empId,
                    ];
                }, $empIds);

                // Insert all users in a single query
                DB::table('hierarchy_user')->insert($insertData);
            }
        }
    }

    private function saveHierarchyObjectType($hierarchyControlId, $request)
    {
        if (!empty($request->object_ids)) {
            $objectTypeIds = json_decode($request->object_ids, true);

            if (is_array($objectTypeIds)) {
                // Delete all existing users for this pay period schedule
                DB::table('hierarchy_object_type')
                    ->where('hierarchy_control_id', $hierarchyControlId)
                    ->whereIn('object_type_id', $objectTypeIds)
                    ->delete();

                // Prepare bulk insert data
                $insertData = array_map(function ($objectId) use ($hierarchyControlId) {

                    return [
                        'hierarchy_control_id' => $hierarchyControlId,
                        'object_type_id' => $objectId,
                    ];
                }, $objectTypeIds);

                // Insert all users in a single query
                DB::table('hierarchy_object_type')->insert($insertData);
            }
        }
    }

    private function saveHierarchyLevel($hierarchyControlId, $request)
    {
        if (!empty($request->level_list)) {
            // Decode the level_list JSON
            $levelList = json_decode($request->level_list, true);

            if (is_array($levelList)) {
                // Extract unique user IDs from 'level' and 'superior'
                $userIds = collect($levelList)
                    ->flatMap(function ($item) {
                        return [$item['level'], $item['superior']];
                    })
                    ->unique()
                    ->values()
                    ->all();

                // Delete existing hierarchy user entries for the hierarchy_control_id
                DB::table('hierarchy_level')
                    ->where('hierarchy_control_id', $hierarchyControlId)
                    ->whereIn('user_id', $userIds)
                    ->delete();

                // Prepare bulk insert data
                $insertData = collect($levelList)->map(function ($item) use ($hierarchyControlId) {
                    return [
                        'hierarchy_control_id' => $hierarchyControlId,
                        'level' => $item['level'],
                        'user_id' => $item['superior'],
                        'status' => 'active', // Default status
                    ];
                })->toArray();

                // Insert new data into the database
                DB::table('hierarchy_level')->insert($insertData);
            }
        }
    }
}
