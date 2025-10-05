<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ContractSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('de_DE');
        
        // Get all users and categories
        $users = User::all();
        $mainCategories = Category::whereNull('parent_id')->get();
        
        // Her kullanıcıya eşit sayıda contract atayalım (100'er tane)
        $contractsPerUser = 100;
        
        foreach ($users as $user) {
            for ($i = 0; $i < $contractsPerUser; $i++) {
                $mainCategory = $mainCategories->random();
                $subcategory = $mainCategory->subcategories->random();
                
                // Calculate commission based on parent category commission rate
                $commission = ($subcategory->base_commission * $mainCategory->commission_rate / 100);
                
                // Generate a random date within the last year
                $date = Carbon::now()->subDays(rand(0, 365));
                
                Contract::create([
                    'user_id' => $user->id,
                    'category_id' => $mainCategory->id,
                    'subcategory_id' => $subcategory->id,
                    'contract_number' => 'VF-' . $faker->unique()->numerify('######'),
                    'contract_date' => $date,
                    'customer_name' => $faker->name,
                    'commission_amount' => $commission,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
        
        // Her kullanıcıya bugün için 2'şer contract ekleyelim
        foreach ($users as $user) {
            for ($i = 0; $i < 2; $i++) {
                $mainCategory = $mainCategories->random();
                $subcategory = $mainCategory->subcategories->random();
                
                $commission = ($subcategory->base_commission * $subcategory->commission_rate / 100);
                
                Contract::create([
                    'user_id' => $user->id,
                    'category_id' => $mainCategory->id,
                    'subcategory_id' => $subcategory->id,
                    'contract_number' => 'VF-' . $faker->unique()->numerify('######'),
                    'contract_date' => Carbon::today(),
                    'customer_name' => $faker->name,
                    'commission_amount' => $commission,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
} 