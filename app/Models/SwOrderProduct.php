<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sw_order_id',
        'sw_product_id',
        'product_name',
        'quantity',
        'price',
        'options',
    ];

    public function order()
    {
        return $this->belongsTo(SwOrder::class, 'sw_order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(SwProduct::class, 'sw_product_id');
    }
}
