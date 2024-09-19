<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwSubscriptionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'charge_id',
        'payment_channel',
        'amount',
        'refund_amount',
        'refund_note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function userSubscription()
    // {
    //     return $this->belongsTo(SwUserSubscription::class, 'sw_user_subscription_id');
    // }

}
