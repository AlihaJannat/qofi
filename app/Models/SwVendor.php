<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class SwVendor extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOwner()
    {
        return DB::table('sw_shops')->where('id', $this->sw_shop_id)->where('owner_id', $this->id)->first() ? true : false;
    }

    // this method is for shop owners only so he can get his multiple shops
    public function getMyShops(): HasMany
    {
        if (!$this->isOwner()) {
            // Return an empty relationship instance
            return $this->hasMany(SwShop::class, 'owner_id')->whereRaw('0 = 1');
        }
        return $this->hasMany(SwShop::class, 'owner_id');
    }

    // shop user just belongs to one shop at a time
    public function shop(): BelongsTo
    {
        return $this->belongsTo(SwShop::class, 'sw_shop_id');
    }
    public function roleRel(): BelongsTo
    {
        return $this->belongsTo(SwRole::class, 'sw_role_id');
    }
    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
