<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->inStock()->paginate(24);
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'unique:products,sku'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['integer', 'min:0'],
        ]);

        return response()->json(Product::create($data), 201);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'price' => ['sometimes', 'integer', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
        ]));

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
