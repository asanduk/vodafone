<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Mobilfunk',
                'description' => 'Mobilfunk Verträge',
                'subcategories' => [
                    ['name' => 'GigaMobil XS', 'description' => '5GB + Allnet & SMS Flat'],
                    ['name' => 'GigaMobil S', 'description' => '15GB + Allnet & SMS Flat'],
                    ['name' => 'GigaMobil M', 'description' => '30GB + Allnet & SMS Flat'],
                    ['name' => 'GigaMobil L', 'description' => '50GB + Allnet & SMS Flat'],
                    ['name' => 'GigaMobil XL', 'description' => 'Unlimited + Allnet & SMS Flat']
                ]
            ],
            [
                'name' => 'DSL & Kabel',
                'description' => 'Internet und Festnetz Verträge',
                'subcategories' => [
                    ['name' => 'Red Internet 50', 'description' => '50 Mbit/s Download'],
                    ['name' => 'Red Internet 100', 'description' => '100 Mbit/s Download'],
                    ['name' => 'Red Internet 250', 'description' => '250 Mbit/s Download'],
                    ['name' => 'GigaCable Max 1000', 'description' => '1000 Mbit/s Download']
                ]
            ],
            [
                'name' => 'TV',
                'description' => 'GigaTV Pakete',
                'subcategories' => [
                    ['name' => 'GigaTV', 'description' => 'Basis TV Paket'],
                    ['name' => 'GigaTV mit Netflix', 'description' => 'TV + Netflix Standard'],
                    ['name' => 'GigaTV Premium', 'description' => 'TV + Premium Sender'],
                    ['name' => 'GigaTV mit DAZN', 'description' => 'TV + DAZN Vollversion']
                ]
            ],
            [
                'name' => 'Smart Home',
                'description' => 'Smart Home Produkte und Dienste',
                'subcategories' => [
                    ['name' => 'V-Home Basic', 'description' => 'Basis Sicherheitspaket'],
                    ['name' => 'V-Home Premium', 'description' => 'Premium Sicherheitspaket'],
                    ['name' => 'V-Home Camera', 'description' => 'Überwachungskamera'],
                    ['name' => 'V-Home Starter', 'description' => 'Smart Home Starter Kit']
                ]
            ],
            [
                'name' => 'Zusatzoptionen',
                'description' => 'Zusätzliche Dienste und Optionen',
                'subcategories' => [
                    ['name' => 'SecureNet', 'description' => 'Internet Sicherheitspaket'],
                    ['name' => 'International Calls', 'description' => 'Auslands-Flatrate'],
                    ['name' => 'MultiSIM', 'description' => 'Zusätzliche SIM-Karten'],
                    ['name' => 'Smartphone Schutz', 'description' => 'Versicherung für Geräte']
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description']
            ]);

            // Alt kategorileri ekle
            foreach ($categoryData['subcategories'] as $subcategory) {
                Category::create([
                    'name' => $subcategory['name'],
                    'description' => $subcategory['description'],
                    'parent_id' => $category->id
                ]);
            }
        }
    }
} 