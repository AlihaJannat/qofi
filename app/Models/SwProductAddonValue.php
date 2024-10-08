<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwProductAddonValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'sw_addon_id',
        'addon_value',
        'price',
        'status',
        'sw_shop_id',
    ];

    /**
     * Relationship to the Product Addon (SwProductAddon).
     */
    public function addon()
    {
        return $this->belongsTo(SwProductAddon::class, 'sw_addon_id');
    }

    /**
     * Relationship to the Shop (SwShop).
     */
    public function shop()
    {
        return $this->belongsTo(SwShop::class, 'sw_shop_id');
    }
}
