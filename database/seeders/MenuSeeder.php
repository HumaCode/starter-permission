<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Shield\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menus (optional, remove if you want to keep existing)
        // Menu::query()->delete();

        // 1. Dashboard Menu
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
            ]
        );
        $dashboard->permissions()->sync([
            Permission::where('name', 'read.dashboard')->first()->id
        ]);

        // 2. Master Data Menu (Parent)
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
            ]
        );

        // 2.1 Users Submenu
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
                'icon' => null,
                'order' => 1,
                'is_active' => true,
                'level' => 'childmenu',
            ]
        );
        $userList->permissions()->sync([
            Permission::where('name', 'read.user')->first()->id
        ]);

        // 2.1.2 User Roles Child Menu
        $userRoles = Menu::firstOrCreate(
            ['slug' => 'user-roles', 'parent_id' => $users->id],
            [
                'parent_id' => $users->id,
                'name' => 'User Roles',
                'slug' => 'user-roles',
                'route' => 'roles.index',
                'icon' => null,
                'order' => 2,
                'is_active' => true,
                'level' => 'childmenu',
            ]
        );
        $userRoles->permissions()->sync([
            Permission::where('name', 'read.role')->first()->id
        ]);

        // 2.2 Menu Management Submenu
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
        $menuManagement->permissions()->sync([
            Permission::where('name', 'read.menu')->first()->id
        ]);

        // 3. Reports Menu
        $reports = Menu::firstOrCreate(
            ['slug' => 'reports'],
            [
                'name' => 'Reports',
                'slug' => 'reports',
                'route' => null,
                'icon' => 'IconChartBar',
                'order' => 3,
                'is_active' => true,
                'level' => 'menu',
            ]
        );

        // 3.1 User Report Submenu
        $userReport = Menu::firstOrCreate(
            ['slug' => 'user-report', 'parent_id' => $reports->id],
            [
                'parent_id' => $reports->id,
                'name' => 'User Report',
                'slug' => 'user-report',
                'route' => 'reports.users',
                'icon' => 'IconFileText',
                'order' => 1,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );
        $userReport->permissions()->sync([
            Permission::where('name', 'read.report')->first()->id
        ]);

        // 4. Settings Menu
        $settings = Menu::firstOrCreate(
            ['slug' => 'settings'],
            [
                'name' => 'Settings',
                'slug' => 'settings',
                'route' => null,
                'icon' => 'IconSettings',
                'order' => 4,
                'is_active' => true,
                'level' => 'menu',
            ]
        );

        // 4.1 Profile Submenu
        $profile = Menu::firstOrCreate(
            ['slug' => 'profile', 'parent_id' => $settings->id],
            [
                'parent_id' => $settings->id,
                'name' => 'Profile',
                'slug' => 'profile',
                'route' => 'profile.edit',
                'icon' => 'IconUser',
                'order' => 1,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );
        $profile->permissions()->sync([
            Permission::where('name', 'read.profile')->first()->id
        ]);

        // 4.2 System Settings Submenu
        $systemSettings = Menu::firstOrCreate(
            ['slug' => 'system-settings', 'parent_id' => $settings->id],
            [
                'parent_id' => $settings->id,
                'name' => 'System Settings',
                'slug' => 'system-settings',
                'route' => 'settings.index',
                'icon' => 'IconAdjustments',
                'order' => 2,
                'is_active' => true,
                'level' => 'submenu',
            ]
        );
        $systemSettings->permissions()->sync([
            Permission::where('name', 'read.setting')->first()->id
        ]);

        $this->command->info('Menus created successfully!');
    }
}
