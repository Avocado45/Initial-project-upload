<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_name' => fake()->words(3, true),

            'product_year' => fake()->numberBetween(1900,2025),

            'product_price' => fake()->randomFloat(2, 0.1, 10000),

            'category_id' => \App\Models\Category::inRandomOrder()->value('id')
                 ?? \App\Models\Category::factory(),

            'user_id' => User::factory(),
            
            'category_id' => Category::factory(),

        ];
    }
}

