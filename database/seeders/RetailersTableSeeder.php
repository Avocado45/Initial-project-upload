<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Retailer;

class RetailersTableSeeder extends Seeder
{
    public function run(): void
    {
        $retail = [
            ['name' => 'Megashop', 'url' => 'https://ukmegashop.com/'],
            ['name' => 'UK Shop', 'url' => 'https://uk.shop.com/'], 
        ];

        $savedRetailers = [];

        foreach ($retail as $r) {
            $savedRetailers[] = Retailer::firstOrCreate(
                ['name' => $r['name']],
                ['url' => $r['url']]
            );
        }

        $products = Product::all();

        foreach ($products as $product) {
            foreach ($savedRetailers as $retailer) {
                $product->retailers()->syncWithoutDetaching([$retailer->id]);
            }
        }
    }
}