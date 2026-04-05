<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InvitationController extends Controller
{
    use ApiResponses;

    public function inviteEmail(string $email, Group $group)
    {
        Gate::authorize('owner', $group);

    }

    public function joinGroup(string $code, Request $request)
    {
        $user = $request->user();
        $group = Group::where('invitation_code', $code)->firstOrFail();
        abort_if(Gate::allows('member', $group),
            403);

        abort_if(
            $user->groups()
                ->wherePivot('group_id', $group->id)
                ->wherePivot('status', 'inactive')
                ->exists(), 403
        );
        $user->groups()->attach($group->id);

        return $this->successResponse($group);
    }




}
