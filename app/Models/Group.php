<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    //
    protected $fillable = ['name',
        'description',
        'avatar'
    ];


    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class,'group_id');
    }


    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class,'group_id');
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class,'group_id');
    }


}

