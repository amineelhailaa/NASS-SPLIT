<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Expense extends Model
{
    protected $fillable = [
        'group_id',
        'payer_id',
        'category_id',
        'title',
        'description',
        'date',
        'amount',
    ];


    public function payer()
    {
        return $this->belongsTo(Membership::class,'payer_id');
    }


    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class,'group_id');
    }
    public function category(): BelongsTo
    {
      return  $this->belongsTo(Category::class,'category_id');
    }
    //files
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class,'attachable');
    }
}
