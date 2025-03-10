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
        $idColumn = 'id';
        $table = 'hierarchy_control';
        $fields = [
            'hierarchy_control.*',
            'hierarchy_control.name AS hierarchy_name',
            'hierarchy_control.id AS hierarchy_control_id',
        ];

        $connections = [
            'hierarchy_object_type' => [
                'con_fields' => ['hierarchy_object_type.object_type_id', 'hierarchy_object_type.hierarchy_control_id'],  // Selecting object type IDs
                'con_where' => ['hierarchy_object_type.hierarchy_control_id' => 'id'],  // Link condition
                'con_joins' => [
                    'object_type' =>  ['object_type.id', '=', 'hierarchy_object_type.object_type_id']  // Join with object_type table
                ],
                'con_name' => 'hierarchy_objectTypes_details',  // Alias for response
                'except_deleted' => 'all',
            ],

            'hierarchy_user' => [
                'con_fields' => ['hierarchy_user.hierarchy_control_id', 'hierarchy_user.user_id'],  // Selecting user IDs
                'con_where' => ['hierarchy_user.hierarchy_control_id' => 'id'],
                'con_joins' => [
                    'emp_employees' => ['emp_employees.id', '=', 'hierarchy_user.user_id']  // Join with employees table
                ],
                'con_name' => 'hierarchy_users_details',
                'except_deleted' => 'all',
            ],

            'hierarchy_level' => [
                'con_fields' => ['hierarchy_level.id', 'hierarchy_level.hierarchy_control_id', 'hierarchy_level.level', 'hierarchy_level.user_id'],
                'con_where' => ['hierarchy_level.hierarchy_control_id' => 'id'],
                'con_joins' => [
                    'emp_employees' => ['emp_employees.id', '=', 'hierarchy_level.user_id']  // Join with employees table
                ],
                'con_name' => 'hierarchy_levels_details',
                'except_deleted' => true,
            ],
        ];

        // Fetch the hierarchy control details using a common method
        $hierarchy = $this->common->commonGetById($id, $idColumn, $table, $fields, [], [], true, $connections);

        return response()->json(['data' => $hierarchy], 200);
    }


    public function deleteHierarchy($id)
    {
        $whereArr = ['id' => $id];
        $title = 'Hierarchy';
        $table = 'hierarchy_control';

        return $this->common->commonDelete($id, $whereArr, $title, $table);
    }


    public function createHierarchy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
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



    private function saveHierarchyUser($hierarchyControlId, $request)
    {
        if (!empty($request->user_ids)) {
            $empIds = json_decode($request->user_ids, true);

            if (is_array($empIds)) {
                // Delete all existing users 
                DB::table('hierarchy_user')
                    ->where('hierarchy_control_id', $hierarchyControlId)
                    ->whereNotIn('user_id', $empIds)
                    ->delete();

                // Insert only the new user IDs that aren't already in the database for this hierarchy
                $existingUsers = DB::table('hierarchy_user')
                ->where('hierarchy_control_id', $hierarchyControlId)
                ->pluck('user_id')
                ->toArray();

                // Filter out existing users to avoid duplicates
                $newUsers = array_diff($empIds, $existingUsers);

                // Prepare bulk insert data
                $insertData = array_map(function ($empId) use ($hierarchyControlId) {
                    return [
                        'hierarchy_control_id' => $hierarchyControlId,
                        'user_id' => $empId,
                    ];
                }, $newUsers);

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
                    ->whereNotIn('object_type_id', $objectTypeIds)
                    ->delete();

                // Insert only the new object type IDs that aren't already in the database for this hierarchy
                $existingObjectTypes = DB::table('hierarchy_object_type')
                ->where('hierarchy_control_id', $hierarchyControlId)
                ->pluck('object_type_id')
                ->toArray();

                // Filter out existing object types to avoid duplicates
                $newObjectTypes = array_diff($objectTypeIds, $existingObjectTypes);

                // Prepare bulk insert data
                $insertData = array_map(function ($objectId) use ($hierarchyControlId) {
                    return [
                        'hierarchy_control_id' => $hierarchyControlId,
                        'object_type_id' => $objectId,
                    ];
                }, $newObjectTypes);

                // Insert all users in a single query
                DB::table('hierarchy_object_type')->insert($insertData);
            }
        }
    }



    private function saveHierarchyLevel($hierarchyControlId, $request)
    {
        if (!empty($request->level_list)) {
            $levelList = json_decode($request->level_list, true) ?? [];
            $deletedLevels = json_decode($request->removed_levels, true) ?? [];

            // Delete Removed Levels
            if (!empty($deletedLevels)) {
                DB::table('hierarchy_level')
                    ->where('hierarchy_control_id', $hierarchyControlId)
                    ->whereIn('id', $deletedLevels)
                    ->delete();
            }

            // Fetch Existing Levels
            $existingLevels = DB::table('hierarchy_level')
                ->where('hierarchy_control_id', $hierarchyControlId)
                ->pluck('id')
                ->toArray();

            $newLevels = [];
            foreach ($levelList as $item) {
                if (isset($item['id']) && in_array($item['id'], $existingLevels)) {
                    // Update Existing Levels
                    DB::table('hierarchy_level')
                        ->where('id', $item['id'])
                        ->update([
                            'level' => $item['level'],
                            'user_id' => $item['superior'],
                            'status' => 'active',
                        ]);
                } else {
                    // Insert New Levels
                    if (!isset($item['id']) || str_starts_with($item['id'], "new-")) {
                        $newLevels[] = [
                            'hierarchy_control_id' => $hierarchyControlId,
                            'level' => $item['level'],
                            'user_id' => $item['superior'],
                            'status' => 'active',
                        ];
                    }
                }
            }

            // Bulk Insert New Levels
            if (!empty($newLevels)) {
                DB::table('hierarchy_level')->insert($newLevels);
            }
        }
    }



}
