<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => ucfirst(fake()->words(3, true)),
            'sku' => strtoupper(fake()->unique()->bothify('ACM-####-??')),
            'description' => fake()->sentence(12),
            'price' => fake()->numberBetween(800, 18000),
            'stock' => fake()->numberBetween(0, 240),
            'is_active' => fake()->boolean(90),
        ];
    }
}
