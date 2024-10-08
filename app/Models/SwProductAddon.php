<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwProductAddon extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'has_price', 'status'];

    public function addonValues()
    {
        return $this->hasMany(SwProductAddonValue::class, 'sw_addon_id');
    }
}
