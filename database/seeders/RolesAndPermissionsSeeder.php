<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // permissions
        $permissions = [
            'view dashboard',
            'create roles',
            'edit roles',
            'delete roles',
            'view roles',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'view permissions',
            'create users',
            'edit users',
            'delete users',
            'view users',
            'create settings',
            'edit settings',
            'delete settings',
            'view settings',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // assign permissions to admin (all)
        $admin->permissions()->sync(Permission::pluck('id')->toArray());

        // optionally assign subset to user
        $user->permissions()->sync(Permission::whereIn('name', ['create settings', 'edit settings', 'delete settings', 'view settings'])->pluck('id')->toArray());

        // attach role to first user if exists
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('admin');
        }
    }
}