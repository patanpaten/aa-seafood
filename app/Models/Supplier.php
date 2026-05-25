<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'contact', 'address'];

    public function incomingStocks()
    {
        return $this->hasMany(IncomingStock::class);
    }
}
