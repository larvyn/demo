<?php

// Fills the demo app's laravel.log and failed_jobs so the Logs and Queues screens have content.
// Run from the demo app: php <path>/demo-seed.php && php artisan queue:work --stop-when-empty --tries=1

use App\Jobs\SendOrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

$root = getcwd();
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (Order::take(3)->get() as $order) {
    SendOrderConfirmation::dispatch($order);
}

Log::info('Checkout completed', ['order' => 'ACM-48213', 'total' => 4870]);
Log::warning('Low stock for SKU ACM-2291-KX', ['stock' => 2]);
try {
    Product::findOrFail(9999);
} catch (Throwable $e) {
    report($e);
}
Log::info('Inventory sync finished', ['updated' => 48]);
try {
    DB::select('select * from shipments where order_id = ?', [42]);
} catch (Throwable $e) {
    report($e);
}
Log::error('Payment webhook signature mismatch', ['provider' => 'stripe', 'event' => 'charge.succeeded']);
