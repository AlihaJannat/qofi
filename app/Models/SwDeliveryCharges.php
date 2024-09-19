<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwDeliveryCharges extends Model
{
    use HasFactory;

    const DELIVERY_FREE = 'free_delivery';
    const DELIVERY_CHARGED = 'flat_charge';
    const DELIVERY_OVER_AMOUNT = 'over_amount';

    protected $fillable=[
        'sw_shop_id',
        'sw_vendor_id',
        'delivery_charges_type',
        'max_amount',
        'status'
    ];

    
    public function shop(): BelongsTo
    {
        return $this->belongsTo(SwShop::class, 'sw_shop_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(SwVendor::class, 'sw_vendor_id');
    }

}
