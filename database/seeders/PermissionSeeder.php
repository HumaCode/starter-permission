<?php

namespace Database\Seeders;

use App\Models\Shield\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'dashboard',
            'product',     // ← Tambah ini
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

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'id' => (string) Str::uuid(),
                    'name' => "{$action}.{$module}",
                    'guard_name' => 'web',
                ];
            }
        }

        $additionalPermissions = [
            ['id' => (string) Str::uuid(), 'name' => 'export.report', 'guard_name' => 'web'],
            ['id' => (string) Str::uuid(), 'name' => 'import.user', 'guard_name' => 'web'],
            ['id' => (string) Str::uuid(), 'name' => 'restore.user', 'guard_name' => 'web'],
            ['id' => (string) Str::uuid(), 'name' => 'forceDelete.user', 'guard_name' => 'web'],
        ];

        $permissions = array_merge($permissions, $additionalPermissions);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }

        $this->command->info('Permissions created successfully!');
        $this->command->info('Total permissions: ' . count($permissions));
    }
}

// ```

// ## 📊 Struktur Menu yang Dihasilkan
// ```
// ┌─────────────────────────────┐
// │ Dashboard                   │ ← No category, direct link
// ├─────────────────────────────┤
// │ MASTER                      │ ← Category header
// │   📦 Produk                 │ ← Direct link (no children)
// ├─────────────────────────────┤
// │ ROLE PERMISSION             │ ← Category header
// │   🛡️ Role                   │ ← Direct link (no children)
// │   🔒 Permission             │ ← Direct link (no children)
// │   ✅ User Akses             │ ← Direct link (no children)
// ├─────────────────────────────┤
// │ SETTING                     │ ← Category header
// │   👥 User                   │ ← Direct link (no children)
// │   🌐 Setting Website        │ ← Direct link (no children)
// └─────────────────────────────┘