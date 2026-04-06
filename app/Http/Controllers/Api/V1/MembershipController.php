<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Membership;
use App\Services\MembershipService;
use Illuminate\Support\Facades\Gate;

class MembershipController extends Controller
{
    use ApiResponses;

    public function __construct(private MembershipService $membershipService) {}

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

        $splitsAsCreditor = $membership->splitsAsCreditor()->sum('amount');
        $splitsAsDebtor = $membership->splitsAsDebtor()->sum('amount');
        $paymentsReceived = $membership->paymentsAsCreditor()->sum('amount');
        $paymentsMade = $membership->paymentsAsDebtor()->sum('amount');
        $totalCredits = $splitsAsCreditor + $paymentsMade;
        $totalDebits = $splitsAsDebtor + $paymentsReceived;
        $netBalances = ($totalCredits - $totalDebits);
        if()

        $this->membershipService->deactivateMembership($membership);

        return $this->successResponse(null, 'Member kicked');
    }
}
