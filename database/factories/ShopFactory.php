<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company;
        return [
            'user_id' => null, // Set in seeder
            'name' => $name,
            'slug' => Str::slug($name),
            'wallet_balance' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
