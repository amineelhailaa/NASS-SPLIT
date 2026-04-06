<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\User;

class MembershipService
{
    public function deactivateMembership(Membership $membership): void
    {
        $membership->update([
            'status' => 'inactive',
            'left_at' => now(),
        ]);
    }

    public function deactivateAllMemberships(User $user): void
    {
        $user->memberships()
            ->where('status', 'active')
            ->update([
                'status' => 'inactive',
                'left_at' => now(),
            ]);
    }
}
