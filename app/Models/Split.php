<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Split extends Model
{
    protected $fillable = [
        'expense_id',
        'debtor_id',
        'amount',
        'status',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    public function debor(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'debtor_id');
    }
}
