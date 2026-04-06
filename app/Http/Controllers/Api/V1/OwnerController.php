<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TransferOwnershipRequest;
use App\Models\Group;
use App\Models\Membership;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    use ApiResponses;

    public function eligilbeUsers(Group $group)
    {
        $elgibleUsers = $group->members()->where('status', 'active')->where('role', '!=', 'owner')->with('user.avatar')->get();

        return $this->successResponse($elgibleUsers); // focus on membership ids to send them
    }

    public function transferOwnership(Group $group, TransferOwnershipRequest $request)
    {
        $user = $request->user();
        $oldOwnerMembership = $group->members()->where('user_id', $user->id)->firstOrFail();
        $newOwnerMembership = Membership::findOrFail($request->membership_id);
        if ($newOwnerMembership->status != 'active') {
            return $this->errorResponse('member is inactive', 403);
        }
        if ($newOwnerMembership->group_id != $group->id) {
            return $this->errorResponse('user not from this group', 403);
        }
        $oldOwnerMembership->update(['role' => 'member']);
        $newOwnerMembership->update(['role' => 'owner']);

        return $this->noContentResponse();
    }
}
