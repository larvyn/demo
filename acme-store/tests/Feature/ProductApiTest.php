<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_products_in_stock(): void
    {
        Product::factory(3)->create(['stock' => 5]);
        Product::factory()->create(['stock' => 0]);

        $this->getJson('/products')->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_it_shows_a_product_with_its_category(): void
    {
        $product = Product::factory()->create();

        $this->getJson("/products/{$product->id}")->assertOk()->assertJsonPath('category.id', $product->category_id);
    }

    public function test_it_creates_a_product(): void
    {
        $category = Category::factory()->create();

        $this->postJson('/products', ['category_id' => $category->id, 'name' => 'Ethiopia Yirgacheffe', 'sku' => 'ACM-0001-ET', 'price' => 1890])
            ->assertCreated();

        $this->assertDatabaseHas('products', ['sku' => 'ACM-0001-ET']);
    }

    public function test_it_rejects_a_duplicate_sku(): void
    {
        $product = Product::factory()->create();

        $this->postJson('/products', ['category_id' => $product->category_id, 'name' => 'Copy', 'sku' => $product->sku, 'price' => 100])
            ->assertJsonValidationErrors('sku');
    }

    public function test_it_hides_inactive_products(): void
    {
        Product::factory(2)->create(['stock' => 5]);
        Product::factory()->create(['stock' => 5, 'is_active' => false]);

        $this->getJson('/products')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_it_updates_the_price(): void
    {
        $product = Product::factory()->create(['price' => 1000]);

        $this->patchJson("/products/{$product->id}", ['price' => 1200])->assertOk()->assertJsonPath('price', 1200);
    }

    public function test_it_deletes_a_product(): void
    {
        $product = Product::factory()->create();

        $this->deleteJson("/products/{$product->id}")->assertNoContent();
        $this->assertModelMissing($product);
    }
}
