<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::apiResource('products', ProductController::class);
Route::post('/checkout', CheckoutController::class)->name('checkout');
