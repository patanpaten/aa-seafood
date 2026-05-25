<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingStock extends Model
{
    protected $fillable = [
        'date',
        'supplier_id',
        'category_id',
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
        'receipt_weight' => 'decimal:2',
        'actual_weight' => 'decimal:2',
        'shrinkage_weight' => 'decimal:2',
    ];
}
