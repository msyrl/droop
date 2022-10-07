<?php

namespace App\Models;

use App\Enums\SalesOrderStatusEnum;
use App\Helpers\CurrencyHelper;
use App\Traits\HasUuid;
use Carbon\Carbon;
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

    private int $totalAttachment;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lineItems(): HasMany
    {
        return $this
            ->hasMany(CartLineItem::class)
            ->orderBy('created_at');
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
        /** @var CartLineItem */
        $existingLineItem = $this
            ->lineItems()
            ->where('product_id', $product->id)
            ->first();

        if ($existingLineItem) {
            $existingLineItem->quantity += $quantity;
            $existingLineItem->total_price = $product->price * $existingLineItem->quantity;
            $existingLineItem->save();
        } else {
            $this->lineItems()->create([
                'product_id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'total_price' => $product->price * $quantity,
            ]);
        }


        $this->update([
            'quantity' => $this->lineItems()->sum('quantity'),
            'total_price' => $this->lineItems()->sum('total_price'),
        ]);
    }

    public function updateLineItem(Product $product, int $quantity): void
    {
        $this
            ->lineItems()
            ->where('product_id', $product->id)
            ->update([
                'quantity' => $quantity,
                'total_price' => $product->price * $quantity,
            ]);

        $this->update([
            'quantity' => $this->lineItems()->sum('quantity'),
            'total_price' => $this->lineItems()->sum('total_price'),
        ]);
    }

    public function deleteLineItem(string $productId): void
    {
        $this
            ->lineItems()
            ->where('product_id', $productId)
            ->delete();

        if ($this->lineItems()->count()) {
            $this->update([
                'quantity' => $this->lineItems()->sum('quantity'),
                'total_price' => $this->lineItems()->sum('total_price'),
            ]);
        } else {
            $this->delete();
        }
    }

    public function checkout(): SalesOrder
    {
        /** @var SalesOrder */
        $salesOrder = SalesOrder::create([
            'user_id' => $this->user_id,
            'status' => SalesOrderStatusEnum::waiting(),
            'paid' => false,
            'name' => Carbon::now()->format('YmdHis'),
        ]);

        $this
            ->lineItems
            ->each(function (CartLineItem $cartLineItem) use ($salesOrder) {
                /** @var Product */
                $product = Product::findOrFail($cartLineItem->product_id);

                $quantity = intval($cartLineItem->quantity);

                $salesOrder->lineItems()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'total_price' => $product->price * $quantity,
                ]);

                $cartLineItem->delete();
            });


        $salesOrder->quantity = $salesOrder->lineItems()->sum('quantity');
        $salesOrder->total_line_items_price = $salesOrder->lineItems()->sum('total_price');
        $salesOrder->total_additional_charges_price = SalesOrder::getDefaultAdditionalCharge() * $this->totalAttachment;
        $salesOrder->total_price = $salesOrder->total_line_items_price + $salesOrder->total_additional_charges_price;
        $salesOrder->save();
        $this->delete();

        return $salesOrder;
    }

    public function setTotalAttachment(int $totalAttachment): Cart
    {
        $this->totalAttachment = $totalAttachment;

        return $this;
    }
}
