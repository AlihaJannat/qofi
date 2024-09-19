<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SwRole extends Model
{
    use HasFactory, SoftDeletes;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(SwPermission::class, 'permission_role', 'sw_role_id', 'sw_permission_id');
    }
}
