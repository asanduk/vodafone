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
                'branch' => 'Berlin Zentrum',
                'phone' => '+49 30 12345678',
                'address' => 'Unter den Linden 1, 10117 Berlin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'anna.schmidt@example.com'],
            [
                'name' => 'Anna Schmidt',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'branch' => 'München Hauptbahnhof',
                'phone' => '+49 89 87654321',
                'address' => 'Bayerstraße 10, 80335 München',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'thomas.weber@example.com'],
            [
                'name' => 'Thomas Weber',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'branch' => 'Hamburg Alstertal',
                'phone' => '+49 40 11223344',
                'address' => 'Heidbergstraße 15, 22391 Hamburg',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'sarah.mueller@example.com'],
            [
                'name' => 'Sarah Müller',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'branch' => 'Köln Domplatz',
                'phone' => '+49 221 55667788',
                'address' => 'Domplatz 5, 50667 Köln',
                'is_active' => true,
            ]
        );
    }
} 