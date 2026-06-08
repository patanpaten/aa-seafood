<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'group_name',
        'retail_price',
        'wholesale_price',
        'image_path',
    ];

    protected $casts = [
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
    ];

    public function setGroupNameAttribute($value): void
    {
        $this->attributes['group_name'] = Str::title(Str::lower(Str::squish((string) $value)));
    }

    public function getDisplayGroupNameAttribute(): string
    {
        return Str::title(Str::lower(Str::squish((string) $this->group_name)));
    }

    public function incomingStocks()
    {
        return $this->hasMany(IncomingStock::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function adjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    public function getPriceForType(string $priceType): float
    {
        return (float) match ($priceType) {
            'grosir' => $this->wholesale_price,
            default => $this->retail_price,
        };
    }

    /**
     * Calculate current available stock for this category.
     * Formula: Sum(Incoming) - Sum(Sales) + Sum(Adjustments Difference)
     */
    public function getCurrentStockAttribute()
    {
        $incoming = $this->incomingStocks()->sum('actual_weight');
        $sales = $this->sales()->sum('quantity_sold_kg');
        $adjustments = $this->adjustments()->sum('difference');

        return $incoming - $sales + $adjustments;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
