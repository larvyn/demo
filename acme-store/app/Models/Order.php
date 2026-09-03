<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'number', 'status', 'tracking_number', 'total', 'paid_at', 'shipped_at'];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total' => 'integer',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recalculate(): void
    {
        $this->update(['total' => $this->items->sum(fn (OrderItem $item) => $item->quantity * $item->unit_price)]);
    }
}
