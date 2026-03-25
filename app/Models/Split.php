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
}
