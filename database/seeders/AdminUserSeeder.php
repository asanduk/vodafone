<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Admin kullanıcı - sadece yoksa oluştur
        User::firstOrCreate(
            ['email' => 'asanduk@gmx.de'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('somepassword'),
                'is_admin' => true,
            ]
        );
        

        // Normal kullanıcılar - sadece yoksa oluştur
        User::firstOrCreate(
            ['email' => 'max.mustermann@example.com'],
            [
                'name' => 'Max Mustermann',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'anna.schmidt@example.com'],
            [
                'name' => 'Anna Schmidt',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'thomas.weber@example.com'],
            [
                'name' => 'Thomas Weber',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'sarah.mueller@example.com'],
            [
                'name' => 'Sarah Müller',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );
    }
} 