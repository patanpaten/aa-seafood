<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'date',
        'partner_id',
        'buyer_name',
        'category_id',
        'price_type',
        'quantity_sold_kg',
        'price_per_kg',
        'total_price',
        'status',          
    'delivery_proof',
    'driver_name',  
    'driver_phone',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getDisplayBuyerNameAttribute(): string
    {
        return $this->buyer_name ?: ($this->partner?->name ?? 'Pembeli Umum');
    }

    protected $casts = [
        'date' => 'date',
        'quantity_sold_kg' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];
}
