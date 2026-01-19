<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper function to get permission ID safely
        $getPermission = function ($permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if (!$permission) {
                $this->command->warn("Permission '{$permissionName}' not found. Skipping...");
                return null;
            }
            return $permission->id;
        };

        // ==========================================
        // NO CATEGORY (Top Level Menus)
        // ==========================================

        // 1. Dashboard Menu (No Category)
        $dashboard = Menu::firstOrCreate(
            ['slug' => 'dashboard'],
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'route' => 'dashboard',
                'icon' => 'IconLayoutDashboard',
                'order' => 1,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => null, // ← No category
            ]
        );

        $dashboardPermission = $getPermission('read.dashboard');
        if ($dashboardPermission) {
            $dashboard->permissions()->sync([$dashboardPermission]);
        }

        // ==========================================
        // MASTER CATEGORY
        // ==========================================

        // 2. Master Data Menu (Parent with Children)
        $masterData = Menu::firstOrCreate(
            ['slug' => 'master-data'],
            [
                'name' => 'Master Data',
                'slug' => 'master-data',
                'route' => null,
                'icon' => 'IconDatabase',
                'order' => 2,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Master'],
            ]
        );

        // 2.1 Users Submenu (Has Children)
        $users = Menu::firstOrCreate(
            ['slug' => 'users', 'parent_id' => $masterData->id],
            [
                'parent_id' => $masterData->id,
                'name' => 'Users',
                'slug' => 'users',
                'route' => null,
                'icon' => 'IconUsers',
                'order' => 1,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );

        // 2.1.1 User List Child Menu
        $userList = Menu::firstOrCreate(
            ['slug' => 'user-list', 'parent_id' => $users->id],
            [
                'parent_id' => $users->id,
                'name' => 'User List',
                'slug' => 'user-list',
                'route' => 'users.index',
                'icon' => 'IconList',
                'order' => 1,
                'is_active' => true,
                'level' => 'childmenu',
            ]
        );

        $userListPermission = $getPermission('read.user');
        if ($userListPermission) {
            $userList->permissions()->sync([$userListPermission]);
        }

        // 2.1.2 User Roles Child Menu
        $userRoles = Menu::firstOrCreate(
            ['slug' => 'user-roles', 'parent_id' => $users->id],
            [
                'parent_id' => $users->id,
                'name' => 'User Roles',
                'slug' => 'user-roles',
                'route' => 'roles.index',
                'icon' => 'IconShield',
                'order' => 2,
                'is_active' => true,
                'level' => 'childmenu',
            ]
        );

        $userRolesPermission = $getPermission('read.role');
        if ($userRolesPermission) {
            $userRoles->permissions()->sync([$userRolesPermission]);
        }

        // 2.2 Menu Management Submenu (No Children - Direct Link)
        $menuManagement = Menu::firstOrCreate(
            ['slug' => 'menu-management', 'parent_id' => $masterData->id],
            [
                'parent_id' => $masterData->id,
                'name' => 'Menu Management',
                'slug' => 'menu-management',
                'route' => 'menus.index',
                'icon' => 'IconMenu2',
                'order' => 2,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );

        $menuManagementPermission = $getPermission('read.menu');
        if ($menuManagementPermission) {
            $menuManagement->permissions()->sync([$menuManagementPermission]);
        }

        // ==========================================
        // SETTINGS CATEGORY
        // ==========================================

        // 3. Settings Menu (Parent)
        $settings = Menu::firstOrCreate(
            ['slug' => 'settings'],
            [
                'name' => 'Settings',
                'slug' => 'settings',
                'route' => null,
                'icon' => 'IconSettings',
                'order' => 3,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Settings'],
            ]
        );

        // 3.1 Website Settings Submenu (Direct Link)
        $websiteSettings = Menu::firstOrCreate(
            ['slug' => 'website-settings', 'parent_id' => $settings->id],
            [
                'parent_id' => $settings->id,
                'name' => 'Website',
                'slug' => 'website-settings',
                'route' => 'settings.website',
                'icon' => 'IconWorld',
                'order' => 1,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );

        $websitePermission = $getPermission('read.setting');
        if ($websitePermission) {
            $websiteSettings->permissions()->sync([$websitePermission]);
        }

        // 3.2 Profile Submenu (Direct Link)
        $profile = Menu::firstOrCreate(
            ['slug' => 'profile', 'parent_id' => $settings->id],
            [
                'parent_id' => $settings->id,
                'name' => 'Profile',
                'slug' => 'profile',
                'route' => 'profile.edit',
                'icon' => 'IconUser',
                'order' => 2,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );

        $profilePermission = $getPermission('read.profile');
        if ($profilePermission) {
            $profile->permissions()->sync([$profilePermission]);
        }

        // ==========================================
        // REPORTS CATEGORY
        // ==========================================

        // 4. Reports Menu (Parent)
        $reports = Menu::firstOrCreate(
            ['slug' => 'reports'],
            [
                'name' => 'Reports',
                'slug' => 'reports',
                'route' => null,
                'icon' => 'IconChartBar',
                'order' => 4,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Reports'],
            ]
        );

        // 4.1 Sales Report Submenu (Has Children)
        $salesReport = Menu::firstOrCreate(
            ['slug' => 'sales-report', 'parent_id' => $reports->id],
            [
                'parent_id' => $reports->id,
                'name' => 'Sales Report',
                'slug' => 'sales-report',
                'route' => null,
                'icon' => 'IconShoppingCart',
                'order' => 1,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );

        // 4.1.1 Monthly Report Child Menu
        $monthlyReport = Menu::firstOrCreate(
            ['slug' => 'monthly-report', 'parent_id' => $salesReport->id],
            [
                'parent_id' => $salesReport->id,
                'name' => 'Monthly',
                'slug' => 'monthly-report',
                'route' => 'reports.monthly',
                'icon' => 'IconCalendar',
                'order' => 1,
                'is_active' => true,
                'level' => 'childmenu',
            ]
        );

        $monthlyPermission = $getPermission('read.report');
        if ($monthlyPermission) {
            $monthlyReport->permissions()->sync([$monthlyPermission]);
        }

        // 4.1.2 Daily Report Child Menu
        $dailyReport = Menu::firstOrCreate(
            ['slug' => 'daily-report', 'parent_id' => $salesReport->id],
            [
                'parent_id' => $salesReport->id,
                'name' => 'Daily',
                'slug' => 'daily-report',
                'route' => 'reports.daily',
                'icon' => 'IconCalendarEvent',
                'order' => 2,
                'is_active' => true,
                'level' => 'childmenu',
            ]
        );

        $dailyPermission = $getPermission('read.report');
        if ($dailyPermission) {
            $dailyReport->permissions()->sync([$dailyPermission]);
        }

        $this->command->info('Menus created successfully with categories!');
    }
}
