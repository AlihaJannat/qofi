<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwAttribute extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'slug',
        'status',
        'image',
        'sw_product_attribute_set_id'
    ];

    
    public function productAttributeSet(): BelongsTo
    {
        return $this->belongsTo(SwProductAttributeSet::class , 'sw_product_attribute_set_id');
    }
}
