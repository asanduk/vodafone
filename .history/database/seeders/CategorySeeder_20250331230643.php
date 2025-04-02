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
                    [
                        'name' => 'GigaMobil XS',
                        'base_commission' => 100,
                        'commission_rate' => 10
                    ],
                    [
                        'name' => 'GigaMobil S',
                        'base_commission' => 150,
                        'commission_rate' => 12
                    ],
                    [
                        'name' => 'GigaMobil M',
                        'base_commission' => 200,
                        'commission_rate' => 15
                    ],
                    [
                        'name' => 'GigaMobil L',
                        'base_commission' => 250,
                        'commission_rate' => 18
                    ]
                ]
            ],
            [
                'name' => 'DSL & Kabel',
                'description' => 'Internet Verträge',
                'subcategories' => [
                    [
                        'name' => 'Red Internet 50',
                        'base_commission' => 120,
                        'commission_rate' => 10
                    ],
                    [
                        'name' => 'Red Internet 100',
                        'base_commission' => 180,
                        'commission_rate' => 12
                    ],
                    [
                        'name' => 'Red Internet 250',
                        'base_commission' => 240,
                        'commission_rate' => 15
                    ],
                    [
                        'name' => 'GigaCable Max 1000',
                        'base_commission' => 300,
                        'commission_rate' => 18
                    ]
                ]
            ],
            [
                'name' => 'TV',
                'description' => 'TV Pakete',
                'subcategories' => [
                    [
                        'name' => 'GigaTV',
                        'base_commission' => 80,
                        'commission_rate' => 10
                    ],
                    [
                        'name' => 'GigaTV mit Netflix',
                        'base_commission' => 100,
                        'commission_rate' => 12
                    ],
                    [
                        'name' => 'GigaTV mit DAZN',
                        'base_commission' => 120,
                        'commission_rate' => 15
                    ]
                ]
            ],
            [
                'name' => 'Smart Home',
                'description' => 'Smart Home Produkte',
                'subcategories' => [
                    [
                        'name' => 'V-Home Basic',
                        'base_commission' => 50,
                        'commission_rate' => 10
                    ],
                    [
                        'name' => 'V-Home Premium',
                        'base_commission' => 80,
                        'commission_rate' => 12
                    ],
                    [
                        'name' => 'V-Home Security',
                        'base_commission' => 100,
                        'commission_rate' => 15
                    ]
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description']
            ]);

            foreach ($categoryData['subcategories'] as $subcategoryData) {
                Category::create([
                    'name' => $subcategoryData['name'],
                    'description' => 'Unterkategorie von ' . $categoryData['name'],
                    'parent_id' => $category->id,
                    'base_commission' => $subcategoryData['base_commission'],
                    'commission_rate' => $subcategoryData['commission_rate']
                ]);
            }
        }
    }
} 