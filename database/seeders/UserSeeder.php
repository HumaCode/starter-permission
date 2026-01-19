<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrator User
        $administrator = User::firstOrCreate(
            ['email'                => 'administrator@cuan.test'],
            [
                'name'              => 'Super Administrator',
                'email'             => 'administrator@cuan.test',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'remember_token'    => Str::random(10),
            ]
        );
        $administrator->assignRole('administrator');

        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@cuan.test'],
            [
                'name'              => 'Admin User',
                'email'             => 'admin@cuan.test',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'remember_token'    => Str::random(10),
            ]
        );
        $admin->assignRole('admin');

        // Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@cuan.test'],
            [
                'name'              => 'Regular User',
                'email'             => 'user@cuan.test',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'remember_token'    => Str::random(10),
            ]
        );
        $user->assignRole('user');

        $this->command->info('Users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Administrator - administrator@cuan.test : password');
        $this->command->info('Admin - admin@cuan.test : password');
        $this->command->info('User - user@cuan.test : password');
    }
}
