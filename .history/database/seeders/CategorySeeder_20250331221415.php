<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Ana kategoriler ve alt kategorileri
        $categories = [
            [
                'name' => 'Mobilfunk',
                'description' => 'Mobilfunk Verträge',
                'subcategories' => [
                    'GigaMobil XS',
                    'GigaMobil S',
                    'GigaMobil M',
                    'GigaMobil L',
                    'GigaMobil XL'
                ]
            ],
            [
                'name' => 'DSL & Kabel',
                'description' => 'Internet und Festnetz Verträge',
                'subcategories' => [
                    'Red Internet 50',
                    'Red Internet 100',
                    'Red Internet 250',
                    'CableMax 1000'
                ]
            ],
            [
                'name' => 'TV',
                'description' => 'GigaTV Pakete',
                'subcategories' => [
                    'GigaTV',
                    'GigaTV mit Netflix',
                    'GigaTV mit DAZN',
                    'GigaTV Premium'
                ]
            ],
            [
                'name' => 'Smart Home',
                'description' => 'Smart Home Produkte und Dienste',
                'subcategories' => [
                    'V-Home Basic',
                    'V-Home Premium',
                    'V-Home Security',
                    'V-Home Camera'
                ]
            ],
            [
                'name' => 'Zubehör',
                'description' => 'Zusatzoptionen und Geräte',
                'subcategories' => [
                    'Handys & Tablets',
                    'Router & Hardware',
                    'Versicherungen',
                    'Zubehör'
                ]
            ]
        ];

        foreach ($categories as $category) {
            // Ana kategoriyi oluştur
            $mainCategory = Category::create([
                'name' => $category['name'],
                'description' => $category['description']
            ]);

            // Alt kategorileri oluştur
            foreach ($category['subcategories'] as $subcategoryName) {
                Category::create([
                    'name' => $subcategoryName,
                    'description' => 'Unterkategorie von ' . $category['name'],
                    'parent_id' => $mainCategory->id
                ]);
            }
        }
    }
} 