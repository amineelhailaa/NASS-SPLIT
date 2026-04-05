<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Mail\GroupInvitationMail;
use App\Models\Group;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    use ApiResponses;

    public function pendingInvitations(Group $group)
    {
        Gate::authorize('owner', $group);

        $invitations = Invitation::where('group_id', $group->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->get();

        return $this->successResponse($invitations);
    }

    public function cancelInvitation(Group $group, Invitation $invitation)
    {
        Gate::authorize('owner', $group);

        if ($invitation->group_id !== $group->id) {
            return $this->errorResponse('Invitation does not belong to this group', 403);
        }

        if ($invitation->status !== 'pending') {
            return $this->errorResponse('This invitation is not pending', 422);
        }

        $invitation->update(['status' => 'cancelled']);

        return $this->successResponse(null, 'Invitation cancelled');
    }

    public function show(string $token, Request $request)
    {
        $result = $this->validateInvitation($token, $request);
        if ($result instanceof JsonResponse) {
            return $result;
        }

        return $this->successResponse($result);
    }

    public function inviteEmail(Request $request, Group $group)
    {
        Gate::authorize('owner', $group);
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);
        $email = $validated['email'];

        if ($group->members()->whereHas('user',
            fn ($query) => $query
                ->where('email', $email))
            ->exists()) {
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
        if (Gate::allows('member', $group)) {
            return $this->errorResponse('already member', 403);
        }

        $exist = $user->groups()
            ->wherePivot('group_id', $group->id)
            ->wherePivot('status', 'inactive')
            ->exists();

        if ($exist) {
            return $this->errorResponse('already kicked member', 403);
        }
        $user->groups()->attach($group->id);

        return $this->successResponse($group, 'You are joined the Group '.$group->name);
    }

    public function joinByInvitation(string $token, Request $request)
    {
        $result = $this->validateInvitation($token, $request);
        if ($result instanceof JsonResponse) {
            return $result;
        }
        $invitation = $result;

        $request->user()->groups()->attach($invitation->group_id, [
            'role' => 'member',
            'status' => 'active',
        ]);
        $invitation->update(['status' => 'accepted']);

        return $this->successResponse($invitation->group,
            'Successfully joined the group.', 201);
    }

    public function declineInvitation(string $token, Request $request)
    {
        $result = $this->validateInvitation($token, $request);
        if ($result instanceof JsonResponse) {
            return $result;
        }

        $result->update(['status' => 'declined']);

        return $this->successResponse(null, 'Invitation declined');
    }

    private function validateInvitation(string $token, Request $request)
    {
        $user = $request->user();
        $invitation = Invitation::with('group')->where('token',
            $token)->firstOrFail();

        if ($invitation->email !== $user->email) {
            return $this->errorResponse('This invitation is not for your account', 403);
        }

        if ($invitation->status !== 'pending') {
            return $this->errorResponse('This invitation has already been used or cancelled', 403);
        }

        if ($invitation->expires_at->isPast()) {
            $invitation->update(['status' => 'expired']);

            return $this->errorResponse('Invitation expired', 403);
        }

        $alreadyMember = $user->groups()->wherePivot('group_id',
            $invitation->group_id)->exists();
        if ($alreadyMember) {
            return $this->errorResponse('You were or are a member',
                403);
        }

        return $invitation;
    }
}
