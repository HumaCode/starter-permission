<?php

namespace Database\Seeders;

use App\Models\Shield\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define modules and their CRUD permissions
        $modules = [
            'dashboard',
            'user',
            'role',
            'permission',
            'menu',
            'profile',
            'setting',
            'report',
        ];

        $actions = ['read', 'create', 'update', 'delete', 'deleteAny'];

        $permissions = [];

        // Generate permissions for each module
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => "{$action}.{$module}",
                    'guard_name' => 'web',
                ];
            }
        }

        // Additional specific permissions
        $additionalPermissions = [
            ['name' => 'export.report', 'guard_name' => 'web'],
            ['name' => 'import.user', 'guard_name' => 'web'],
            ['name' => 'restore.user', 'guard_name' => 'web'],
            ['name' => 'forceDelete.user', 'guard_name' => 'web'],
        ];

        $permissions = array_merge($permissions, $additionalPermissions);

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }

        $this->command->info('Permissions created successfully!');
    }
}
