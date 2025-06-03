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
            'users.view',
            'users.super',
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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
