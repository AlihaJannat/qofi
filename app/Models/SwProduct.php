<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(SwShop::class, 'sw_shop_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(SwProductImage::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(SwCategory::class, 'sw_product_category', 'sw_product_id', 'sw_category_id');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(SwColor::class, 'sw_product_color', 'sw_product_id', 'sw_color_id');
    }

    public function toppings()
    {
        return $this->hasMany(SwProductTopping::class);
    }

    public function heights(): HasMany
    {
        return $this->hasMany(SwProductHeight::class);
    }

    public function defaultHeight(): HasOne
    {
        return $this->hasOne(SwProductHeight::class)->orderByDesc('is_default');
    }

    public function addImages($filname)
    {
        SwProductImage::create([
            'sw_product_id' => $this->id,
            'image_name' => "/products/additional/" . $filname
        ]);
    }

    public function getVariationIds()
    {
        if ($this->has_variation == 1) {

            return $this->where('parent_variation', $this->id)->pluck('id');
        } else {
            return [];
        }
    }
}
