<?php

namespace App\Models;

use App\Enums\SalesOrderStatusEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'total_price',
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
        return number_format($this->quantity);
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price);
    }

    public function getFormattedHasAttachmentAttribute(): string
    {
        return $this->hasAttachment() ? __('YES') : __('NO');
    }

    public function getAttachmentUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('attachment');
    }

    public function setAttachment(UploadedFile $uploadedFile): void
    {
        $this->clearMediaCollection('attachment');
        $this
            ->addMedia($uploadedFile)
            ->toMediaCollection('attachment');
    }

    public function hasAttachment(): bool
    {
        return boolval($this->getFirstMedia('attachment'));
    }
}