<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariations extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_variation_set_id',
        'variation_id',
        'is_default',
        'parent_product_id'
    ];


    public function productAttributeSet(): BelongsTo
    {
        return $this->belongsTo(SwProductAttributeSet::class , 'product_variation_set_id');
    }
    
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(SwAttribute::class , 'variation_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(SwProduct::class , 'product_id');
    }


}
