<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'School Principal',
            'email' => 'principal@school.edu',
            'password' => Hash::make('password'),
            'role' => 'principal',
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@school.edu',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}