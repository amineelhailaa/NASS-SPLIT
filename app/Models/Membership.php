<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Membership extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'role',
        'left_at',
        'balance',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentsAsDebtor(): HasMany
    {
        return $this->hasMany(Payment::class, 'debtor_id');
    }

    public function paymentsAsCreditor(): HasMany
    {
        return $this->hasMany(Payment::class, 'creditor_id');
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class, 'group_id', 'group_id'); // need lockup
    }

    public function splitsAsDebtor(): HasMany
    {
        return $this->hasMany(Split::class, 'debtor_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function expensesPaid(): HasMany
    {
        return $this->hasMany(Expense::class, 'payer_id');
    }

    public function splitsAsCreditor(): HasManyThrough
    {
        return $this->hasManyThrough(Split::class,
            Expense::class,
            'payer_id',
            'expense_id',
            'id', // membership->id
            'id' // expense.id
        );
    }
}
