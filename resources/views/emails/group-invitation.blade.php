<x-mail::message>
# You have been invited!

**{{ $sender->name }}** has invited you to join a group on **{{ config('app.name') }}**.

---

**Group:** {{ $groupName }}
**Invited by:** {{ $sender->name }}

---

Click the button below to accept the invitation and join the group.

<x-mail::button :url="$joinUrl" color="primary">
Accept Invitation
</x-mail::button>

If the button does not work, copy and paste this link into your browser:

{{ $joinUrl }}

---

If you were not expecting this invitation or do not know {{ $sender->name }}, you can safely ignore this email.

Thanks,
**{{ config('app.name') }}**
</x-mail::message>