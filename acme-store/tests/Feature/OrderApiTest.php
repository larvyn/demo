<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_paginates_orders(): void
    {
        Order::factory(60)->create();

        $this->getJson('/api/orders')->assertOk()->assertJsonCount(50, 'data');
    }

    public function test_it_shows_an_order_with_its_items(): void
    {
        $order = Order::factory()->create();

        $this->getJson("/api/orders/{$order->id}")->assertOk()->assertJsonPath('number', $order->number);
    }

    public function test_it_returns_404_for_a_missing_order(): void
    {
        $this->getJson('/api/orders/999')->assertNotFound();
    }
}
