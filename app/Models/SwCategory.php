<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwCategory extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    protected $guarded = [];

    // public function products(): BelongsToMany
    // {
    //     return $this->belongsToMany(SwProduct::class, 'sw_product_category', 'sw_category_id', 'sw_product_id');
    // }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SwCategory::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(SwCategory::class, 'parent_id', 'id')->where('status', 1);
    }

    public function products(): HasMany
    {
        return $this->hasMany(SwProduct::class);
    }

    public function navProducts(): HasMany
    {
        // return $this->hasMany(SwProduct::class)
        //     ->where('status', 1);

        return $this->hasMany(SwProduct::class)
            ->join('sw_shops', 'sw_shops.id', 'sw_products.sw_shop_id')
            ->orderByDesc('is_featured')
            ->where('sw_products.status', 1)
            ->limit(4)
            ->select('sw_products.id', 'sw_products.sw_category_id', 'sw_products.name', 'sw_products.slug', 'sw_products.image_name', 'sw_products.short_description', 'sw_shops.slug as shop_slug');
    }
}
