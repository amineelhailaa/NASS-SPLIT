<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;

class GroupService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function createGroup(User $user,$data)
    {
        $group =  Group::create([ // should verify if i can createw grp with that relation !
            'name' => $data->name,
            'description' => $data->description,
        ]);
        $user->groups()->attach($group->id,['role'=>'owner']);
        return $group;
    }
}
