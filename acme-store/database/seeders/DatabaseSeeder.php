<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['name' => 'Store Admin', 'email' => 'admin@acme.test']);
        User::factory(4)->create();

        $categories = Category::factory(6)->create();
        $products = Product::factory(48)->recycle($categories)->create();
        $customers = Customer::factory(120)->create();

        Order::factory(340)->recycle($customers)->create()->each(function (Order $order) use ($products) {
            foreach ($products->random(fake()->numberBetween(1, 4)) as $product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => fake()->numberBetween(1, 3),
                    'unit_price' => $product->price,
                ]);
            }
            $order->recalculate();
        });
    }
}
