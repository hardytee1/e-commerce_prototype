<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'shop_id' => null, // Set in seeder
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
