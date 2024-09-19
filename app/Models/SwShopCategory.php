<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwShopCategory extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function shops():HasMany
    {
        return $this->hasMany(SwShop::class);
    }
}
