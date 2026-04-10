<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'users:read',
            'users:write',
            'users:create',
            'users:edit',
            'users:delete',

            'roles:read',
            'roles:write',
            'roles:create',
            'roles:edit',
            'roles:delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign created permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        Role::firstOrCreate(['name' => 'user']);

        // Create Admin User
        $admin = User::firstOrCreate([
            'email' => 'admin@parkir2077.test',
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');
    }
}
