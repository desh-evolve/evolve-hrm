<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view permission', ['only' => ['index']]);
        $this->middleware('permission:create permission', ['only' => ['create','store']]);
        $this->middleware('permission:update permission', ['only' => ['update','edit']]);
        $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::orderBy('type')->get()->groupBy('type');
        return view('role-permission.permission.index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return view('role-permission.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => [
                'required',
                'string',
            ],
            'names' => [
                'required',
                'array',
                'min:1', // Ensure there is at least one permission
            ],
            'names.*' => [
                'required',
                'string',
                'unique:permissions,name',
            ],
        ]);
    
        foreach ($request->names as $name) {
            Permission::create([
                'name' => $name,
                'type' => $request->type,
            ]);
        }
    
        return redirect('permissions')->with('status', 'Permissions Created Successfully');
    }       

    public function edit(Permission $permission)
    {
        return view('role-permission.permission.edit', [
            'permission' => $permission, 
            'type' => $permission->type
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,'.$permission->id
            ],
            'type' => [
                'required',
                'string'
            ]
        ]);

        $permission->update([
            'name' => $request->name,
            'type' => $request->type
        ]);

        return redirect('permissions')->with('status','Permission Updated Successfully');
    }

    public function destroy($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();
        return redirect('permissions')->with('status','Permission Deleted Successfully');
    }
}