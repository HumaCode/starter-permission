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
        // Dashboard
        $dashboard = Menu::create([
            'name' => 'Dashboard',
            'slug' => 'dashboard',
            'route' => 'dashboard',
            'icon' => 'LayoutDashboard',
            'order' => 1,
        ]);
        $dashboard->permissions()->attach(
            Permission::where('name', 'read.dashboard')->first()
        );

        // Master Data (Parent)
        $masterData = Menu::create([
            'name' => 'Master Data',
            'slug' => 'master-data',
            'icon' => 'Database',
            'order' => 2,
        ]);

        // Master Data > Users (Submenu)
        $users = Menu::create([
            'parent_id' => $masterData->id,
            'name' => 'Users',
            'slug' => 'users',
            'icon' => 'Users',
            'order' => 1,
        ]);

        // Master Data > Users > User List (Childmenu)
        $userList = Menu::create([
            'parent_id' => $users->id,
            'name' => 'User List',
            'slug' => 'user-list',
            'route' => 'users.index',
            'order' => 1,
        ]);
        $userList->permissions()->attach(
            Permission::where('name', 'read.user')->first()
        );

        // Master Data > Users > User Roles (Childmenu)
        $userRoles = Menu::create([
            'parent_id' => $users->id,
            'name' => 'User Roles',
            'slug' => 'user-roles',
            'route' => 'roles.index',
            'order' => 2,
        ]);
        $userRoles->permissions()->attach(
            Permission::where('name', 'read.role')->first()
        );
    }
}
