<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in order
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // Create test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Password: password
        ]);

        // Assign admin role to test user
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $testUser->assignRole($adminRole);
            // Also sync permissions directly
            $permissions = Permission::all();
            $testUser->syncPermissions($permissions);
        }
    }
}
