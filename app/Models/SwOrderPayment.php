<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwOrderPayment extends Model
{
    use HasFactory;

    const STATUS_PAID = 'Paid';
    const STATUS_UNPAID = 'Unpaid';
    const STATUS_FAILED = 'Failed';

    protected $fillable = [
        'user_id',
        'sw_order_id',
        'currency',
        'charge_id',
        'payment_channel',
        'amount',
        'refund_amount',
        'refund_note',
        'payment_status',
        'status',
    ];

    public function paymentMethod()
    {
        if ($this->payment_channel == 'cod')
            return 'Cash On Delivery';
        else
            return 'Stripe';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(SwOrder::class, 'sw_order_id');
    }
}
