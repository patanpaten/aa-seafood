<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'date',
        'partner_id',
        'category_id',
        'price_type',
        'quantity_sold_kg',
        'price_per_kg',
        'total_price',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'date' => 'date',
        'quantity_sold_kg' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];
}
