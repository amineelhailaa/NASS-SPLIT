<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
        'provider_name',
        'provider_id',
        'ban',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class,'user_id');
    }

    //relationships
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class,'memberships')
            ->withPivot(['id','role','left_at','status'])
            ->withTimestamps();
    }

    public function conversations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Conversation::class,
            Membership::class,
            'user_id', //memberships
            'group_id', //fk final table
            'id', //actual table
            'group_id' //fk memberships
        )
            ->where('memberships.status','active');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class,'user_id');
    }


    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class,'user_id');
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(Attachment::class,'attachable');
    }



}
