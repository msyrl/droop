<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'user_id',
        'quantity',
        'total_price',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(CartLineItem::class);
    }

    public function getFormattedQuantityAttribute(): string
    {
        return CurrencyHelper::format($this->quantity);
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return CurrencyHelper::format($this->total_price);
    }

    public function addLineItem(Product $product, int $quantity): void
    {
        $this->lineItems()->create([
            'product_id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'total_price' => $product->price * $quantity,
        ]);

        $this->update([
            'quantity' => $this->lineItems()->sum('quantity'),
            'total_price' => $this->lineItems()->sum('total_price'),
        ]);
    }
}
