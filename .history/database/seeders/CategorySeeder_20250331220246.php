<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Ana kategoriler
        $mainCategories = [
            [
                'name' => 'DSL Verträge',
                'description' => 'Internet und Festnetz Verträge',
                'subcategories' => [
                    'Red Internet & Phone 16 DSL',
                    'Red Internet & Phone 50 DSL',
                    'Red Internet & Phone 100 DSL',
                    'Red Internet & Phone 250 DSL'
                ]
            ],
            [
                'name' => 'Mobilfunk Verträge',
                'description' => 'Mobilfunk Verträge und Tarife',
                'subcategories' => [
                    'GigaMobil S',
                    'GigaMobil M',
                    'GigaMobil L',
                    'GigaMobil XL'
                ]
            ],
            [
                'name' => 'TV Pakete',
                'description' => 'GigaTV Pakete und Zusatzoptionen',
                'subcategories' => [
                    'GigaTV',
                    'GigaTV mit Netflix',
                    'GigaTV mit DAZN',
                    'GigaTV Premium'
                ]
            ],
            [
                'name' => 'Business Lösungen',
                'description' => 'Geschäftskunden Verträge',
                'subcategories' => [
                    'Business Start DSL',
                    'Business Premium DSL',
                    'Business Mobile',
                    'Business Complete'
                ]
            ],
            [
                'name' => 'Zusatzoptionen',
                'description' => 'Zusätzliche Dienste und Optionen',
                'subcategories' => [
                    'Sicherheitspaket',
                    'International Calls',
                    'SecureNet',
                    'Cloud Backup'
                ]
            ]
        ];

        foreach ($mainCategories as $mainCategory) {
            $category = Category::create([
                'name' => $mainCategory['name'],
                'description' => $mainCategory['description']
            ]);

            // Alt kategorileri ekle
            foreach ($mainCategory['subcategories'] as $subcategoryName) {
                Category::create([
                    'name' => $subcategoryName,
                    'description' => 'Unterkategorie von ' . $mainCategory['name'],
                    'parent_id' => $category->id
                ]);
            }
        }
    }
} 