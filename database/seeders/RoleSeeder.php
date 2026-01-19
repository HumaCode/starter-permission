<?php

namespace Database\Seeders;

use App\Models\Shield\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            [
                'name'          => 'administrator',
                'guard_name'    => 'web',
            ],
            [
                'name'          => 'admin',
                'guard_name'    => 'web',
            ],
            [
                'name'          => 'user',
                'guard_name'    => 'web',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                $role
            );
        }

        $this->command->info('Roles created successfully!');
    }
}
