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
        $getPermission = function($permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if (!$permission) {
                $this->command->warn("Permission '{$permissionName}' not found. Skipping...");
                return null;
            }
            return $permission->id;
        };

        // ==========================================
        // DASHBOARD (No Category)
        // ==========================================

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
                'metadata' => null,
            ]
        );

        $dashboardPermission = $getPermission('read.dashboard');
        if ($dashboardPermission) {
            $dashboard->permissions()->sync([$dashboardPermission]);
        }

        // ==========================================
        // MASTER CATEGORY
        // ==========================================

        // Produk Menu (Direct Link - No Children)
        $produk = Menu::firstOrCreate(
            ['slug' => 'produk'],
            [
                'name' => 'Produk',
                'slug' => 'produk',
                'route' => 'products.index',
                'icon' => 'IconPackage',
                'order' => 2,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Master'],
            ]
        );

        $produkPermission = $getPermission('read.product');
        if ($produkPermission) {
            $produk->permissions()->sync([$produkPermission]);
        }

        // ==========================================
        // ROLE PERMISSION CATEGORY
        // ==========================================

        // Role Menu (Direct Link - No Children)
        $role = Menu::firstOrCreate(
            ['slug' => 'role'],
            [
                'name' => 'Role',
                'slug' => 'role',
                'route' => 'roles.index',
                'icon' => 'IconShield',
                'order' => 3,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Role Permission'],
            ]
        );

        $rolePermission = $getPermission('read.role');
        if ($rolePermission) {
            $role->permissions()->sync([$rolePermission]);
        }

        // Permission Menu (Direct Link - No Children)
        $permission = Menu::firstOrCreate(
            ['slug' => 'permission'],
            [
                'name' => 'Permission',
                'slug' => 'permission',
                'route' => 'permissions.index',
                'icon' => 'IconLock',
                'order' => 4,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Role Permission'],
            ]
        );

        $permissionPermission = $getPermission('read.permission');
        if ($permissionPermission) {
            $permission->permissions()->sync([$permissionPermission]);
        }

        // User Akses Menu (Direct Link - No Children)
        $userAkses = Menu::firstOrCreate(
            ['slug' => 'user-akses'],
            [
                'name' => 'User Akses',
                'slug' => 'user-akses',
                'route' => 'user-akses.index',
                'icon' => 'IconUserCheck',
                'order' => 5,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Role Permission'],
            ]
        );

        $userAksesPermission = $getPermission('read.user');
        if ($userAksesPermission) {
            $userAkses->permissions()->sync([$userAksesPermission]);
        }

        // ==========================================
        // SETTING CATEGORY
        // ==========================================

        // User Menu (Direct Link - No Children)
        $user = Menu::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'User',
                'slug' => 'user',
                'route' => 'users.index',
                'icon' => 'IconUsers',
                'order' => 6,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Setting'],
            ]
        );

        $userPermission = $getPermission('read.user');
        if ($userPermission) {
            $user->permissions()->sync([$userPermission]);
        }

        // Setting Website Menu (Direct Link - No Children)
        $settingWebsite = Menu::firstOrCreate(
            ['slug' => 'setting-website'],
            [
                'name' => 'Setting Website',
                'slug' => 'setting-website',
                'route' => 'settings.website',
                'icon' => 'IconWorld',
                'order' => 7,
                'is_active' => true,
                'level' => 'menu',
                'metadata' => ['category' => 'Setting'],
            ]
        );

        $settingPermission = $getPermission('read.setting');
        if ($settingPermission) {
            $settingWebsite->permissions()->sync([$settingPermission]);
        }

        $this->command->info('Menus created successfully with flat structure!');
    }
}