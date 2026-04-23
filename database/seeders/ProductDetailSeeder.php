<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductDetail;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        ProductDetail::create([
            'product_id' => 1,
            'manufacturer'=> 'PepsiCo',
            'description'=>'Fizzy Cola flavoured soft drink.',
            ]);

    }
}
