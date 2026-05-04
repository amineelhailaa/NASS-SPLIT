<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'creditor_id',
        'debtor_id',
        'amount',
        'status',
    ];

    public function creditor(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'creditor_id');
    }

    public function debtor(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'debtor_id');
    }
}
