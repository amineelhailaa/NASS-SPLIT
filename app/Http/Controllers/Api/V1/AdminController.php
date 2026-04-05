<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    use ApiResponses;

    public MembershipService $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;

    }

    public function banUser(User $user, Request $request)
    {
        Gate::authorize('admin');
        if ($user->ban == 1) {
            return $this->errorResponse('user already banned', 403);
        }
        $user->update(['ban' => 1]);
        $this->membershipService->deactivateAllMemberships($user);

        return $this->successResponse($user, 'user banned');

    }

    public function unBanUser(User $user, Request $request)
    {
        // even unbanned user wont recover the memberships so
        Gate::authorize('admin');
        if ($user->ban == 0) {
            return $this->errorResponse('user not banned to unban him', 403);
        }
        $user->update(['ban' => 0]);

        return $this->successResponse($user, 'user unbanned');

    }
}
