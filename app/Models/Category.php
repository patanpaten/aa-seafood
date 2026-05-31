<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'group_name',
        'price',
        'retail_price',
        'wholesale_price',
        'image_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
    ];

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
