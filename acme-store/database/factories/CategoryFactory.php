<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Coffee', 'Tea', 'Brewing gear', 'Mugs', 'Gift cards', 'Accessories']);

        return ['name' => $name, 'slug' => Str::slug($name)];
    }
}
