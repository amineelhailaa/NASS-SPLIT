<?php

namespace App\Services;

class GroupService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function createGroup($user,$data)
    {
        return $user->groups()->create([ // should verify if i can createw grp with that relation !
            'name' => $data->name,
            'description' => $data->description,
        ]);
    }
}
