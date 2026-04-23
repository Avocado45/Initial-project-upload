<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Consumer Electronics',
            'Clothing',
            'Home Appliance',
            'Outdoors',
            'Vehicles',
            'Health',
            'Toys & Games',
        ];

        return [
            'category_name' => fake()->randomElement($categories),
        ];
    }
}
