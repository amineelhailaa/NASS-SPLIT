<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'file_name',
        'file_type',
        'path',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo(); // by default use the function name to find id and type
    }
}
