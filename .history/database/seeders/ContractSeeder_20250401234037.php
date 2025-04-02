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
        
        // Generate contracts for the past year
        for ($i = 0; $i < 500; $i++) {
            $user = $users->random();
            $mainCategory = $mainCategories->random();
            $subcategory = $mainCategory->subcategories->random();
            
            // Calculate commission based on subcategory rates
            $commission = ($subcategory->base_commission * $subcategory->commission_rate / 100);
            
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
        
        // Generate some contracts for today (for testing purposes)
        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
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