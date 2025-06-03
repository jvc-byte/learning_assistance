<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            'admin' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.delete',
                'lessons.view',
                'lessons.create',
                'lessons.edit',
                'lessons.delete',
                'roles.view',
                'roles.create',
                'roles.edit',
                'roles.delete',
                'lessons.view',
                'lessons.create',
                'lessons.edit',
                'lessons.delete',
            ],
            'user' => [
                'users.view',
                'lessons.view',
                'roles.view',
            ],
        ];

        // Create and assign permissions to roles
        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            
            foreach ($permissions as $permission) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm) {
                    $role->givePermissionTo($perm);
                }
            }
        }

        // Assign admin role to test user
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $testUser->assignRole($adminRole);
                // Also sync permissions directly
                $permissions = Permission::all();
                $testUser->syncPermissions($permissions);
            }
        }
    }
}
