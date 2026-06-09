<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingStock extends Model
{
    protected $fillable = [
        'date',
        'supplier_id',
        'category_id',
        'purchase_price_per_kg',
        'total_purchase_price',
        'receipt_weight',
        'actual_weight',
        'shrinkage_weight',
        'status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'date' => 'date',
        'purchase_price_per_kg' => 'decimal:2',
        'total_purchase_price' => 'decimal:2',
        'receipt_weight' => 'decimal:2',
        'actual_weight' => 'decimal:2',
        'shrinkage_weight' => 'decimal:2',
    ];
}
