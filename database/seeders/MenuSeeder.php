<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Shield\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Starting menu seeding...');

        // Clear pivot table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('menu_permission')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Helper function with DIRECT SQL INSERT
        $syncPermission = function($menu, $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();

            if (!$permission) {
                $this->command->error("❌ Permission '{$permissionName}' not found!");
                return false;
            }

            // Debug
            $this->command->info("   Permission: {$permission->name}");
            $this->command->info("   Permission ID: {$permission->id}");
            $this->command->info("   Menu ID: {$menu->id}");

            try {
                // Use DB::insert with EXPLICIT string binding
                DB::insert(
                    'INSERT INTO menu_permission (menu_id, permission_id, created_at, updated_at) VALUES (?, ?, ?, ?)',
                    [
                        $menu->id,              // Already UUID string
                        $permission->id,        // Already UUID string
                        now(),
                        now()
                    ]
                );

                $this->command->info("   ✅ Inserted");

                // Verify what was actually inserted
                $verify = DB::select(
                    'SELECT menu_id, permission_id FROM menu_permission WHERE menu_id = ? LIMIT 1',
                    [$menu->id]
                );

                if (!empty($verify)) {
                    $this->command->info("   ✅ Verified - permission_id: {$verify[0]->permission_id}");
                } else {
                    $this->command->error("   ❌ Verification failed - record not found");
                }

                return true;
            } catch (\Exception $e) {
                $this->command->error("   ❌ Error: " . $e->getMessage());
                return false;
            }
        };

        // Create menus
        $menus = [
            [
                'slug' => 'dashboard',
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'IconLayoutDashboard',
                'order' => 1,
                'category' => null,
                'permission' => 'read.dashboard',
            ],
            [
                'slug' => 'produk',
                'name' => 'Produk',
                'route' => 'products.index',
                'icon' => 'IconPackage',
                'order' => 2,
                'category' => 'Master',
                'permission' => 'read.product',
            ],
            [
                'slug' => 'menu',
                'name' => 'Menu',
                'route' => 'menus.index',
                'icon' => 'IconMenu',
                'order' => 3,
                'category' => 'Master',
                'permission' => 'read.menu',
            ],
            [
                'slug' => 'role',
                'name' => 'Role',
                'route' => 'roles.index',
                'icon' => 'IconShield',
                'order' => 4,
                'category' => 'Role Permission',
                'permission' => 'read.role',
            ],
            [
                'slug' => 'permission',
                'name' => 'Permission',
                'route' => 'permissions.index',
                'icon' => 'IconLock',
                'order' => 5,
                'category' => 'Role Permission',
                'permission' => 'read.permission',
            ],
            [
                'slug' => 'user-akses',
                'name' => 'User Akses',
                'route' => 'user-akses.index',
                'icon' => 'IconUserCheck',
                'order' => 6,
                'category' => 'Role Permission',
                'permission' => 'read.user',
            ],
            [
                'slug' => 'user',
                'name' => 'User',
                'route' => 'users.index',
                'icon' => 'IconUsers',
                'order' => 7,
                'category' => 'Setting',
                'permission' => 'read.user',
            ],
            [
                'slug' => 'setting-website',
                'name' => 'Setting Website',
                'route' => 'settings.website',
                'icon' => 'IconWorld',
                'order' => 8,
                'category' => 'Setting',
                'permission' => 'read.setting',
            ],
        ];

        foreach ($menus as $menuData) {
            $this->command->info("\n📍 Creating {$menuData['name']}...");

            $menu = Menu::updateOrCreate(
                ['slug' => $menuData['slug']],
                [
                    'name' => $menuData['name'],
                    'slug' => $menuData['slug'],
                    'route' => $menuData['route'],
                    'icon' => $menuData['icon'],
                    'order' => $menuData['order'],
                    'is_active' => true,
                    'level' => 'menu',
                    'metadata' => $menuData['category'] ? ['category' => $menuData['category']] : null,
                ]
            );

            $syncPermission($menu, $menuData['permission']);
        }

        // Final summary
        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info('📊 FINAL SUMMARY');
        $this->command->info(str_repeat('=', 60));

        $totalPivots = DB::table('menu_permission')->count();
        $this->command->info("Total pivot records: {$totalPivots}");

        $pivots = DB::select('SELECT menu_id, permission_id FROM menu_permission');
        foreach ($pivots as $pivot) {
            $menu = Menu::find($pivot->menu_id);
            $perm = Permission::find($pivot->permission_id);

            $menuName = $menu ? $menu->name : 'UNKNOWN';
            $permName = $perm ? $perm->name : 'UNKNOWN';

            $this->command->info("{$menuName} <-> {$permName}");
            $this->command->info("  IDs: {$pivot->menu_id} <-> {$pivot->permission_id}");
        }
    }
}