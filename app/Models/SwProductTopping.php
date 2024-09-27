<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwProductTopping extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'status', 'product_id'];


    public function product()
    {
        return $this->belongsTo(SwProduct::class);
    }
}
