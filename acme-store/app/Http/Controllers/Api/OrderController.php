<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('customer')->latest()->paginate(50);
    }

    public function show(Order $order)
    {
        return $order->load('customer', 'items.product');
    }
}
