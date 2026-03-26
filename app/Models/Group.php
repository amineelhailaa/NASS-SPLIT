<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Random\RandomException;

class Group extends Model
{
    //
    protected $fillable = [
        'name',
        'avatar',
        'description',
        'invitation_code',
        'status',
    ];

    //generate code

    public function generateCodeInvitation():void {
        do{
            $inv_code = random_int(111111,999999);
        } while (Group::where('invitation_code',$inv_code)->exists()); //ihave to add index thing after
        $this->invitation_code = $inv_code;
    }
    protected static function booted()
    {
        static::creating(function (Group $group) {
            $group->generateCodeInvitation();
        });
    }

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


    public function ownerMembership(): HasOne
    {
        return $this->hasOne(Membership::class,'group_id')->where('role','owner');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Membership::class,'group_id');
    }


}
