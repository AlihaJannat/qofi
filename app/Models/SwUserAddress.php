<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwUserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'country',
        'status',
        'postal_code',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
