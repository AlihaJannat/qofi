<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwUserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sw_subscription_id',
        'date',
        'sw_subscription_payment_id',
        'status',
    ];

    public function getDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }



    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(SwSubscription::class, 'sw_subscription_id');
    }

    public function payment()
    {
        return $this->belongsTo(SwSubscriptionPayment::class, 'sw_subscription_payment_id');
    }

}
