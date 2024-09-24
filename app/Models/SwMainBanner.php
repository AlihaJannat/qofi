<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwMainBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image', 'has_button', 'button_color', 'button_bg_color', 'button_text', 'sort_order','status'
    ];
}
