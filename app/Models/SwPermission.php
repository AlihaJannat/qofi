<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SwPermission extends Model
{
    use HasFactory;

    protected $table = 'sw_permissions';
    public $timestamps = false;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SwRole::class, 'permission_role', 'sw_permission_id', 'sw_role_id');
    }
}
