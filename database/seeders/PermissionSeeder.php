<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'users.index',
            'users.create',
            'users.edit',
            'users.show',
            'users.destroy',
            'lessons.index',
            'lessons.create',
            'lessons.edit',
            'lessons.show',
            'lessons.destroy',
            "roles.index",
            "roles.create",
            "roles.edit",
            "roles.show",
            "roles.destroy",
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
