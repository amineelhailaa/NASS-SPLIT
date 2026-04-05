<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = [
        'group_id',
        'email',
        'token',
        'status',
        'expires_at',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
