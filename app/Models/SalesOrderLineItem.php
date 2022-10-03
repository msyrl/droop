<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderLineItem extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'quantity',
        'total_price',
    ];

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return CurrencyHelper::format($this->price);
    }

    public function getFormattedQuantityAttribute(): string
    {
        return CurrencyHelper::format($this->quantity);
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return CurrencyHelper::format($this->total_price);
    }
}
