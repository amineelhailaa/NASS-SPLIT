<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    //

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class,'group_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class,'conversation_id');
    }
}
