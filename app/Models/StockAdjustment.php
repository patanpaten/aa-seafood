<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'category_id',
        'previous_stock',
        'actual_stock',
        'difference',
        'reason',
        'adjusted_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}
