<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::where('is_active', true)->first();
        $banners = [
            [
                'product_id' => $product->id,
                'title' => 'Banner Product 1',
                'url' => '/assets/images/products/product1.png',
            ],
            [
                'product_id' => $product->id,
                'title' => 'Banner Product 1',
                'url' => '/assets/images/products/product2.png',
            ],
        ];

        DB::table('product_banners')->insert($banners);
    }
}
