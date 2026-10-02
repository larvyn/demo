<?php

namespace Tests\Feature;

use App\Jobs\SendOrderConfirmation;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_order_and_totals_it(): void
    {
        Queue::fake();
        $product = Product::factory()->create(['price' => 1250, 'stock' => 10]);

        $this->postJson('/checkout', [
            'email' => 'ana@example.com',
            'name' => 'Ana',
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ])->assertCreated()->assertJsonPath('total', 2500);

        Queue::assertPushed(SendOrderConfirmation::class);
    }

    public function test_it_decrements_stock(): void
    {
        Queue::fake();
        $product = Product::factory()->create(['stock' => 10]);

        $this->postJson('/checkout', [
            'email' => 'ana@example.com',
            'name' => 'Ana',
            'items' => [['product_id' => $product->id, 'quantity' => 3]],
        ])->assertCreated();

        $this->assertSame(7, $product->fresh()->stock);
    }

    public function test_it_rejects_items_that_are_out_of_stock(): void
    {
        Queue::fake();
        $product = Product::factory()->create(['stock' => 1]);

        $this->postJson('/checkout', [
            'email' => 'ana@example.com',
            'name' => 'Ana',
            'items' => [['product_id' => $product->id, 'quantity' => 5]],
        ])->assertUnprocessable();

        $this->assertSame(1, $product->fresh()->stock);
    }

    public function test_it_requires_at_least_one_item(): void
    {
        $this->postJson('/checkout', ['email' => 'ana@example.com', 'name' => 'Ana', 'items' => []])
            ->assertJsonValidationErrors('items');
    }
}
