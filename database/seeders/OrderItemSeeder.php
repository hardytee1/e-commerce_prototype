<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();
        foreach ($orders as $order) {
            $product = $products->random();
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'shop_id' => $product->shop_id,
            ]);
        }
    }
}
