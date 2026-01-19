<?php

namespace Database\Seeders;

use App\Models\Shield\Permission;
use App\Models\Shield\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all roles
        $administratorRole  = Role::where('name', 'administrator')->first();
        $adminRole          = Role::where('name', 'admin')->first();
        $userRole           = Role::where('name', 'user')->first();

        // Administrator - Full Access (All Permissions)
        $allPermissions = Permission::all();
        $administratorRole->syncPermissions($allPermissions);

        // Admin - Limited Access
        $adminPermissions = Permission::whereIn('name', [
            // Dashboard
            'read.dashboard',

            // Users
            'read.user',
            'create.user',
            'update.user',
            'delete.user',

            // Roles (read only)
            'read.role',

            // Reports
            'read.report',
            'export.report',

            // Profile
            'read.profile',
            'update.profile',

            // Settings (read only)
            'read.setting',
        ])->get();
        $adminRole->syncPermissions($adminPermissions);

        // User - Very Limited Access
        $userPermissions = Permission::whereIn('name', [
            // Dashboard
            'read.dashboard',

            // Profile only
            'read.profile',
            'update.profile',

            // Reports (read only)
            'read.report',
        ])->get();
        $userRole->syncPermissions($userPermissions);

        $this->command->info('Role permissions assigned successfully!');
        $this->command->info('Administrator: Full access to all permissions');
        $this->command->info('Admin: Limited access (users, reports, basic settings)');
        $this->command->info('User: Minimal access (dashboard, profile, view reports)');
    }
}
