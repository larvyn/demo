<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(OrderStatus::cases());
        $paid = $status !== OrderStatus::Pending ? fake()->dateTimeBetween('-60 days') : null;

        return [
            'customer_id' => Customer::factory(),
            'number' => 'ACM-'.fake()->unique()->numberBetween(10000, 99999),
            'status' => $status,
            'tracking_number' => $status === OrderStatus::Shipped ? strtoupper(fake()->bothify('1Z###??#########')) : null,
            'paid_at' => $paid,
            'shipped_at' => $status === OrderStatus::Shipped ? $paid : null,
        ];
    }
}
