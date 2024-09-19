<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwSubscriptionShop extends Model
{
    use HasFactory;
    protected $fillable = [
        'sw_subscription_id',
        'sw_shop_id',
        'order_count'
    ];
}
