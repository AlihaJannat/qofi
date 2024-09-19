<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwProductHeight extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(SwUnit::class, 'sw_unit_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(SwProduct::class, 'sw_product_id');
    }

    public function getPrice()
    {
        $price = $this->price;

        if (strtolower($this->discount_type) == 'percent') {
            // Calculate price after percentage discount
            $discountedAmount = ($this->discount / 100) * $price;
            $price -= $discountedAmount;
        } else {
            // Apply flat amount discount
            $price -= $this->discount;
        }

        // Ensure price is not negative
        if ($price < 0) {
            $price = 0; // or handle accordingly based on your business logic
        }

        return $price;
    }
}
