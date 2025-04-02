<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Ana kategoriler
        $mobilfunk = Category::create([
            'name' => 'Mobilfunk',
            'description' => 'Mobilfunk Verträge',
            'base_commission' => 100.00,
            'commission_rate' => 10.00
        ]);

        $festnetz = Category::create([
            'name' => 'Festnetz',
            'description' => 'Festnetz Verträge',
            'base_commission' => 80.00,
            'commission_rate' => 8.00
        ]);

        // Alt kategoriler - Mobilfunk
        Category::create([
            'name' => 'Red',
            'description' => 'Vodafone Red Tarife',
            'parent_id' => $mobilfunk->id,
            'base_commission' => 150.00,
            'commission_rate' => 12.00
        ]);

        Category::create([
            'name' => 'Young',
            'description' => 'Vodafone Young Tarife',
            'parent_id' => $mobilfunk->id,
            'base_commission' => 120.00,
            'commission_rate' => 15.00
        ]);

        // Alt kategoriler - Festnetz
        Category::create([
            'name' => 'DSL',
            'description' => 'DSL Anschlüsse',
            'parent_id' => $festnetz->id,
            'base_commission' => 90.00,
            'commission_rate' => 9.00
        ]);

        Category::create([
            'name' => 'Kabel',
            'description' => 'Kabel Anschlüsse',
            'parent_id' => $festnetz->id,
            'base_commission' => 100.00,
            'commission_rate' => 10.00
        ]);
    }
} 