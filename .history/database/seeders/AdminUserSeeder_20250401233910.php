<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@vodafone.de',
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'created_at' => Carbon::now()->subYear(),
            'updated_at' => Carbon::now()->subYear(),
        ]);

        // Regional Admins
        $regions = ['Nord', 'Süd', 'Ost', 'West'];
        foreach ($regions as $region) {
            User::create([
                'name' => "Admin {$region}",
                'email' => "admin.{$region}@vodafone.de",
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'created_at' => Carbon::now()->subYear(),
                'updated_at' => Carbon::now()->subYear(),
            ]);
        }

        // Monthly Admins (created throughout the year)
        $months = 12;
        for ($i = 1; $i <= $months; $i++) {
            User::create([
                'name' => "Admin {$i}",
                'email' => "admin{$i}@vodafone.de",
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'created_at' => Carbon::now()->subMonths($i),
                'updated_at' => Carbon::now()->subMonths($i),
            ]);
        }

        // Support Admins
        User::create([
            'name' => 'Support Admin',
            'email' => 'support@vodafone.de',
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'created_at' => Carbon::now()->subMonths(6),
            'updated_at' => Carbon::now()->subMonths(6),
        ]);

        User::create([
            'name' => 'Technical Support',
            'email' => 'tech.support@vodafone.de',
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'created_at' => Carbon::now()->subMonths(3),
            'updated_at' => Carbon::now()->subMonths(3),
        ]);
    }
} 