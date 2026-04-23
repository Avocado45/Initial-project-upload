<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductTableSeeder extends Seeder
{
    public function run(): void
    {
        
        Product::factory()
            ->count(50)
            ->recycle(Category::all())
            ->create();
    }
}
