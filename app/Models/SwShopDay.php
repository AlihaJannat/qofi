<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwShopDay extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function day(): BelongsTo
    {
        return $this->belongsTo(SwDay::class, 'sw_day_id');
    }

    public function times(): BelongsToMany
    {
        return $this->belongsToMany(SwTime::class, 'sw_shop_day_times', 'sw_shop_day_id', 'sw_time_id');
    }
}
