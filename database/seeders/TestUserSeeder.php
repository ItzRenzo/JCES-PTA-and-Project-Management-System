<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@jcses.edu.ph',
                'password_hash' => Hash::make('password'),
                'user_type' => 'administrator',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'phone' => '09123456789',
                'is_active' => true,
                'created_date' => now(),
            ],
            [
                'username' => 'principal',
                'email' => 'principal@jcses.edu.ph',
                'password_hash' => Hash::make('principal123'),
                'user_type' => 'principal',
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'phone' => '09234567890',
                'is_active' => true,
                'created_date' => now(),
            ],
            [
                'username' => 'teacher',
                'email' => 'teacher@jcses.edu.ph',
                'password_hash' => Hash::make('teacher123'),
                'user_type' => 'teacher',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'phone' => '09345678901',
                'is_active' => true,
                'created_date' => now(),
            ],
            [
                'username' => 'parent',
                'email' => 'parent@gmail.com',
                'password_hash' => Hash::make('parent123'),
                'user_type' => 'parent',
                'first_name' => 'Anna',
                'last_name' => 'Garcia',
                'phone' => '09456789012',
                'is_active' => true,
                'created_date' => now(),
            ],
        ];

        foreach ($users as $user) {
            // Check if user already exists
            $existingUser = DB::table('users')
                ->where('username', $user['username'])
                ->orWhere('email', $user['email'])
                ->first();

            if (!$existingUser) {
                DB::table('users')->insert($user);
                $this->command->info("Created user: {$user['username']} ({$user['user_type']})");
            } else {
                $this->command->info("User already exists: {$user['username']}");
            }
        }
    }
}
