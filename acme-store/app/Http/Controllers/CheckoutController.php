<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Jobs\SendOrderConfirmation;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'name' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $customer = Customer::firstOrCreate(['email' => $data['email']], ['name' => $data['name'], 'country' => 'US']);
            $order = $customer->orders()->create(['number' => 'ACM-'.Str::upper(Str::random(6)), 'status' => OrderStatus::Pending]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->decrement('stock', $item['quantity']);
                $order->items()->create(['product_id' => $product->id, 'quantity' => $item['quantity'], 'unit_price' => $product->price]);
            }
            $order->recalculate();

            return $order;
        });

        SendOrderConfirmation::dispatch($order);

        return response()->json($order->load('items'), 201);
    }
}
