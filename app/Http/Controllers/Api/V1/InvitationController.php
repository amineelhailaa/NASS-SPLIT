<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Mail\GroupInvitationMail;
use App\Models\Group;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    use ApiResponses;

    public function inviteEmail(Request $request, Group $group)
    {
        Gate::authorize('owner', $group);
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);
        $email = $validated['email'];

        if ($group->users()->where('email', $email)->exists()) {
            return $this->errorResponse('User was a member before !', 422);
        }

        $existing = Invitation::where('group_id', $group->id)
            ->where('email', $email)
            ->where('status', 'pending')
            ->where('invitations.expires_at', '>', now())
            ->first();
        if ($existing) {
            return $this->errorResponse('A pending Invitation already for this email', 422);
        }

        Invitation::where('group_id', $group->id)
            ->where('email', $email)
            ->update(['status' => 'expired']);

        $invitation = Invitation::create([
            'group_id' => $group->id,
            'email' => $email,
            'token' => Str::random(64),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);
        Mail::to($email)->send(new GroupInvitationMail($group, $request->user(), $invitation));

        return $this->successResponse(null, 'invitation sent');

    }

    public function joinGroupByCode(string $code, Request $request)
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

    public function joinByInvitation(string $token, Request $request)
    {
        $user = $request->user();
        $invitation = Invitation::where('token', $token)
            ->firstOrFail();

        if ($invitation->email !== $user->email) {
            return $this->errorResponse('This Invitation not for Your Account', 403);
        }
        if ($invitation->status !== 'pending') {
            return $this->errorResponse('this invitation has already been used or cancelled', 403);
        }
        if ($invitation->expires_at->isPast()) {
            $invitation->update(['status' => 'expired']);

            return $this->errorResponse('invitation expired !', 403);
        }
        $user->groups()->attach($invitation->group_id, [
            'role' => 'member',
            'status' => 'active',
        ]);
        $invitation->update(['status' => 'accepted']);

        return $this->successResponse($invitation->group,
            'Successfully joined the group.', 201);

    }
}
