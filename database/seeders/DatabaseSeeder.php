<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Database seeding completed successfully!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('1. Administrator (Full Access)');
        $this->command->info('   Email: administrator@cuan.test');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('2. Admin (Limited Access)');
        $this->command->info('   Email: admin@cuan.test');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('3. User (Minimal Access)');
        $this->command->info('   Email: user@cuan.test');
        $this->command->info('   Password: password');
        $this->command->info('========================================');
    }
}
