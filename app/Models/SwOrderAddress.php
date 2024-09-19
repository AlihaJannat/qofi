<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwOrderAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'sw_order_id',
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'address',
        'status',
        'postal_code',
    ];

    public function order()
    {
        return $this->belongsTo(SwOrder::class, 'sw_order_id');
    }

    public function getFullAddress()
    {
        return $this->address . " " . $this->city . ", " . $this->state . " " . $this->country;
    }
}
