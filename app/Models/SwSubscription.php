<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwSubscription extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'short_description',
        'long_description',
        'duration',
        'price',
        'status',
        'shops_count',
        'cups_count',
        'coffee_type',
    ];



    // public function shops()
    // {
    //     return $this->belongsToMany(SwShop::class, 'sw_subscription_shops', 'sw_subscription_id', 'sw_shop_id')->withPivot('order_count');
    // }


    // public function subscriptionShops()
    // {
    //     return $this->hasMany(SwSubscriptionShop::class, 'sw_subscription_id');
    // }

    public function subscriber()
    {
        return $this->hasMany(SwUserSubscription::class, 'sw_subscription_id');
    }


    // public function validateCounts(array $shopCounts): bool
    // {
    //     $totalCount = array_sum($shopCounts);
    //     return $totalCount <= $this->duration;
    // }
}
