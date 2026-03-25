<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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


}
