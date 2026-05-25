<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

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
}
