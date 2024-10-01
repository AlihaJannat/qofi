<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwRelatedProducts extends Model
{
    use HasFactory;


    public function mainproduct()
    {
        return $this->belongsTo(SwProduct::class, 'sw_product_id');
    }
    public function relatedproduct()
    {
        return $this->belongsTo(SwProduct::class, 'related_product_id');
    }
}
