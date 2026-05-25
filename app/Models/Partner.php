<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = ['name', 'contact', 'address'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
