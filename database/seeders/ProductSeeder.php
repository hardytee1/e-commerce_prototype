<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Shop;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $shops = Shop::all();
        foreach ($shops as $shop) {
            Product::factory(5)->create(['shop_id' => $shop->id]);
        }
    }
}
