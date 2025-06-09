<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\User;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            if (rand(0,1)) {
                Shop::factory()->create(['user_id' => $user->id]);
            }
        }
    }
}
