<?php

namespace App\Models;

use App\Enums\SalesOrderStatusEnum;
use App\Helpers\CurrencyHelper;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SalesOrder extends Model implements HasMedia
{
    use HasFactory;
    use HasUuid;
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'status',
        'paid',
        'name',
        'quantity',
        'total_line_items_price',
        'total_additional_charges_price',
        'total_price',
    ];

    protected $appends = [
        'attachments',
    ];

    protected $casts = [
        'status' => SalesOrderStatusEnum::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(SalesOrderLineItem::class);
    }

    public function getFormattedStatusAttribute(): string
    {
        return strtoupper($this->status);
    }

    public function getFormattedPaidAttribute(): string
    {
        return $this->paid ? __('PAID') : __('UNPAID');
    }

    public function getFormattedQuantityAttribute(): string
    {
        return CurrencyHelper::format($this->quantity);
    }

    public function getFormattedTotalLineItemsPriceAttribute(): string
    {
        return CurrencyHelper::format($this->total_line_items_price);
    }

    public function getFormattedTotalAdditionalChargesPriceAttribute(): string
    {
        return CurrencyHelper::format($this->total_additional_charges_price);
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return CurrencyHelper::format($this->total_price);
    }

    public function getFormattedHasAttachmentAttribute(): string
    {
        return $this->hasAttachment() ? __('YES') : __('NO');
    }

    public function getAttachmentsAttribute(): Collection
    {
        return $this->getMedia('attachment');
    }

    public function getDueDateAttribute(): Carbon
    {
        return $this->created_at->addDays(7);
    }

    public function addAttachment(UploadedFile $uploadedFile): void
    {
        $this
            ->addMedia($uploadedFile)
            ->toMediaCollection('attachment');
    }

    public static function getDefaultAdditionalCharge(): int
    {
        return 1000;
    }
}
