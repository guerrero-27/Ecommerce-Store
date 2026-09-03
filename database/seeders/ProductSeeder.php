<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            ['name' => 'Wireless Mouse', 'price' => 599, 'stock' => 50],
            ['name' => 'mechanical keyboard', 'price' => 2499, 'stock' => 25],
            ['name' => 'Ceramic Mug Set', 'price' => 799, 'compare_price' => 999, 'stock' => 10],
            ['name' => 'Canvas Tote Bag', 'price' => 450, 'stock' => 60],
            ['name' => 'Facial cleanser', 'price' => 350, 'stock' => 100],
            ['name' => 'Yoga Mat', 'price' => 899, 'compare_price' => 1199, 'stock' => 20],
            ['name' => 'Bluetooth Speaker', 'price' => 1899, 'stock' => 15],
            ['name' => 'Scented Candle', 'price' => 299, 'stock' => 80],
        ];

        foreach ($products as $i => $product){
            Product::create([
                'category_id' => $categories->random()->id,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => 'A great quantity' . strtolower($product['name']) . ' for everyday use.',
                'price' => $product['price'],
                'compare_price' => $product['compare_price'] ?? null,
                'stock' => $product['stock'],
                'sku' => 'SKU-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'is_active' =>true,
                'is_featured' => $i < 4,
            ]);
        }
    }
}
