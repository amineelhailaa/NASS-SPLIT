<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Membership;
use App\Services\MembershipService;
use App\Services\SettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MembershipController extends Controller
{
    use ApiResponses;

    public function __construct(
        private MembershipService $membershipService,
        private SettlementService $settlementService
    ) {}

    public function kick(Group $group, Membership $membership)
    {
        Gate::authorize('owner', $group);

        if ($membership->group_id !== $group->id) {
            return $this->errorResponse('Membership does not belong to this group', 403);
        }

        if ($membership->role === 'owner') {
            return $this->errorResponse('Cannot kick the group owner', 403);
        }

        if ($membership->status === 'inactive') {
            return $this->errorResponse('Member is already inactive', 422);
        }

        $netBalance = $this->settlementService->memberBalance($membership);
        if ($netBalance != 0 && $group->settle) {
            return $this->errorResponse('settle rule: Member has unsettled balance ', 422);
        }

        $this->membershipService->deactivateMembership($membership);

        return $this->successResponse(null, 'Member kicked');
    }

    public function leave(Group $group, Request $request)
    {
        Gate::authorize('member', $group);
        $membership = $request->user()
            ->memberships()
            ->where('group_id', $group->id)
            ->firstOrFail();

        if ($membership->status === 'inactive') {
            return $this->errorResponse('Member is already inactive', 422);
        }

        if ($membership->role === 'owner') {
            return $this->errorResponse('Owner cannot leave before transferring ownership', 422);
        }

        $netBalance = $this->settlementService->memberBalance($membership);
        if ($netBalance != 0 && $group->settle) {
            return $this->errorResponse('Settle rule: member has unsettled balance', 422);
        }

        $this->membershipService->deactivateMembership($membership);

        return $this->successResponse(null, 'You left the group');
    }
}
