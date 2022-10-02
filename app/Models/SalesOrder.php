<?php

namespace App\Models;

use App\Enums\SalesOrderStatusEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrder extends Model
{
    use HasFactory;
    use HasUuid;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedStatusAttribute(): string
    {
        return strtoupper($this->status);
    }

    public function getFormattedPaidAttribute(): string
    {
        return $this->paid ? __('YES') : __('NO');
    }
}
