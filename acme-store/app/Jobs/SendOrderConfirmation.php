<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use RuntimeException;

class SendOrderConfirmation implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(public Order $order) {}

    public function handle(): void
    {
        if (! config('services.postmark.key')) {
            throw new RuntimeException("Postmark API key is not configured, cannot email order {$this->order->number}.");
        }
    }
}
