<?php

namespace Database\Seeders;

use App\Models\Shield\Permission;
use App\Models\Shield\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles
        $administrator = Role::where('name', 'administrator')->first();
        $admin = Role::where('name', 'admin')->first();
        $user = Role::where('name', 'user')->first();

        // Get all permissions
        $allPermissions = Permission::all();

        // ==========================================
        // ADMINISTRATOR - Full Access
        // ==========================================
        if ($administrator) {
            $administrator->syncPermissions($allPermissions);
            $this->command->info("Administrator role: {$allPermissions->count()} permissions assigned");
        }

        // ==========================================
        // ADMIN - Limited Access
        // ==========================================
        if ($admin) {
            $adminPermissions = Permission::whereIn('name', [
                // Dashboard
                'read.dashboard',

                // Product
                'read.product',
                'create.product',
                'update.product',
                'delete.product',

                // User
                'read.user',
                'create.user',
                'update.user',

                // Role (read only)
                'read.role',

                // Permission (read only)
                'read.permission',

                // Setting
                'read.setting',
                'update.setting',
            ])->pluck('id');

            $admin->syncPermissions($adminPermissions);
            $this->command->info("Admin role: {$adminPermissions->count()} permissions assigned");
        }

        // ==========================================
        // USER - Minimal Access
        // ==========================================
        if ($user) {
            $userPermissions = Permission::whereIn('name', [
                // Dashboard
                'read.dashboard',

                // Product (read only)
                'read.product',

                // User (read own data only)
                'read.user',
            ])->pluck('id');

            $user->syncPermissions($userPermissions);
            $this->command->info("User role: {$userPermissions->count()} permissions assigned");
        }

        $this->command->info('Role permissions assigned successfully!');
    }
}