<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwFilter extends Model
{
    use HasFactory;

    public function shops() {
        return $this->belongsToMany(SwShop::class, 'sw_shop_filters', 'sw_filter_id', 'sw_shop_id');
    }
}
