<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Traits\LogsActivity;

class RoleController extends Controller
{
    use LogsActivity;

    public function index()
    {
        return response()->json(Role::with('permissions')->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $data['name']]);
        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        // Log activity
        $permissionNames = Permission::whereIn('id', $data['permissions'] ?? [])->pluck('name')->toArray();
        $this->logActivity(
            'created',
            Role::class,
            $role->id,
            "Created role: {$role->name}",
            null,
            [
                'name' => $role->name,
                'permissions' => $permissionNames
            ]
        );

        return response()->json($role->load('permissions'), 201);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Store old values for logging
        $oldPermissions = $role->permissions->pluck('name')->toArray();
        $oldName = $role->name;

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $data['name']]);
        $role->permissions()->sync($data['permissions'] ?? []);

        // Log activity
        $newPermissions = Permission::whereIn('id', $data['permissions'] ?? [])->pluck('name')->toArray();
        $this->logActivity(
            'updated',
            Role::class,
            $role->id,
            "Updated role: {$role->name}",
            [
                'name' => $oldName,
                'permissions' => $oldPermissions
            ],
            [
                'name' => $role->name,
                'permissions' => $newPermissions
            ]
        );

        return response()->json($role->load('permissions'));
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $roleName = $role->name;
        $permissions = $role->permissions->pluck('name')->toArray();

        $role->delete();

        // Log activity
        $this->logActivity(
            'deleted',
            Role::class,
            $id,
            "Deleted role: {$roleName}",
            [
                'name' => $roleName,
                'permissions' => $permissions
            ],
            null
        );

        return response()->json(['message' => 'Role deleted']);
    }
}