<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Product extends Model implements HasMedia
{
    use HasUuid;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'description',
    ];

    protected $appends = [
        'featured_image_url',
        'images',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('featured_image')
            ->useFallbackUrl('/img/logo-500.png');
    }

    public function getFormattedPriceAttribute(): string
    {
        return CurrencyHelper::format($this->price);
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('featured_image');
    }

    /**
     * @return Collection<string>
     */
    public function getImagesAttribute(): Collection
    {
        return $this->getMedia('image');
    }

    public function setFeaturedImage(UploadedFile $uploadedFile): void
    {
        $this->addMedia($uploadedFile)->toMediaCollection('featured_image');
    }

    public function setFeaturedImageFromUrl(string $url): void
    {
        $this->addMediaFromUrl($url)->toMediaCollection('featured_image');
    }

    public function setImage(UploadedFile $uploadedFile): void
    {
        $this->addMedia($uploadedFile)->toMediaCollection('image');
    }

    public function setImageFromUrl(string $url): void
    {
        $this->addMediaFromUrl($url)->toMediaCollection('image');
    }

    public function clearFeaturedImage(): void
    {
        $this->clearMediaCollection('featured_image');
    }

    public function clearImages(array $ids): void
    {
        foreach ($ids as $id) {
            $this->deleteMedia($id);
        }
    }
}
