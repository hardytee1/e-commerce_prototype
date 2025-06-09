<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Shop;
use App\Models\Order;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $shops = Shop::all();
        $orders = Order::all();
        foreach ($users as $user) {
            Transaction::factory()->create([
                'user_id' => $user->id,
                'type' => 'top-up',
            ]);
        }
        foreach ($shops as $shop) {
            Transaction::factory()->create([
                'shop_id' => $shop->id,
                'type' => 'sale_credit',
            ]);
        }
        foreach ($orders as $order) {
            Transaction::factory()->create([
                'order_id' => $order->id,
                'type' => 'purchase',
            ]);
        }
    }
}
