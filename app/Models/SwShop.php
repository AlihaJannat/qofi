<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwShop extends Model
{
    use HasFactory, SoftDeletes;

    public function owner(): BelongsTo
    {
        return $this->belongsTo(SwVendor::class, 'owner_id');
    }


    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function subscriptions()
    {
        return $this->belongsToMany(SwSubscription::class, 'sw_subscription_shops', 'sw_shop_id', 'sw_subscription_id');
    }

    public function deliverycharges(): HasOne
    {
        return $this->hasOne(SwDeliveryCharges::class, 'sw_shop_id', 'id');
    }
}
