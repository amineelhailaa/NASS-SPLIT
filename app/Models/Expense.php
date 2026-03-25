<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expense extends Model
{
    protected $fillable = [
        'group_id',
        'payer_id',
        'category_id',
        'title',
        'description',
        'amount',
    ];

    public function payer(): BelongsTo
    {
        return $this->belongsTo(Membership::class,'payer_id');
    }


    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class,'group_id');
    }

    public function attachment(): HasOne
    {
        return $this->hasOne(Attachment::class,'expense_id');
    }
}
