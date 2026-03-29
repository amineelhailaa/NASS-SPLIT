<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = [
        'group_id',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class,'group_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class,'conversation_id');
    }

    public function lastMessage(): HasOne //forshowing conv list with last message
    {
        return $this->hasOne(Message::class,'conversation_id')->latestOfMany();
    }
}
