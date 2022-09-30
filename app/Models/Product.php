<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'description',
    ];

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price);
    }
}
