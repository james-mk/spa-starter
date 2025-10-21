<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Traits\LogsActivity;

class PermissionController extends Controller
{
    use LogsActivity;

    public function index()
    {
        return response()->json(Permission::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|unique:permissions,name']);
        $perm = Permission::create($data);

        // Log activity
        $this->logActivity(
            'created',
            Permission::class,
            $perm->id,
            "Created permission: {$perm->name}",
            null,
            ['name' => $perm->name]
        );

        return response()->json($perm, 201);
    }

    public function update(Request $request, $id)
    {
        $perm = Permission::findOrFail($id);
        $oldName = $perm->name;

        $data = $request->validate(['name' => 'required|string|unique:permissions,name,' . $perm->id]);
        $perm->update($data);

        // Log activity
        $this->logActivity(
            'updated',
            Permission::class,
            $perm->id,
            "Updated permission from '{$oldName}' to '{$perm->name}'",
            ['name' => $oldName],
            ['name' => $perm->name]
        );

        return response()->json($perm);
    }

    public function destroy($id)
    {
        $perm = Permission::findOrFail($id);
        $permName = $perm->name;

        $perm->delete();

        // Log activity
        $this->logActivity(
            'deleted',
            Permission::class,
            $id,
            "Deleted permission: {$permName}",
            ['name' => $permName],
            null
        );

        return response()->json(['message' => 'Permission deleted']);
    }
}