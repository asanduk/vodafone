<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Admin kullanıcı
        User::create([
            'name' => 'Admin User',
            'email' => 'asanduk@gmx.de',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        // Normal kullanıcılar
        User::create([
            'name' => 'Max Mustermann',
            'email' => 'max.mustermann@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Anna Schmidt',
            'email' => 'anna.schmidt@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Thomas Weber',
            'email' => 'thomas.weber@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Sarah Müller',
            'email' => 'sarah.mueller@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);
    }
} 