@php($title = 'My Groups')

@extends('layouts.app')

@section('content')
    @php($notifications = (int) ($pendingNotificationsCount ?? 0))

    <div class="mx-auto w-full max-w-4xl">
        <x-heading title="My Groups">
            <x-slot:action>
                <div class="flex items-center gap-3">
                    <x-link href="{{ url('/groups/join') }}" class="text-sm">Join Group</x-link>
                    <x-link href="{{ url('/groups/create') }}" class="text-sm">Create Group</x-link>
                    @if($notifications > 0)
                        <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-cerulean-600 px-2 text-xs font-semibold text-white">
                            {{ $notifications }}
                        </span>
                    @endif
                </div>
            </x-slot:action>
        </x-heading>

        <div class="mt-4 grid gap-3 md:grid-cols-2">
            <x-card>
                <p class="text-sm text-cerulean-800">Need a new space for expenses?</p>
                <x-link href="{{ url('/groups/create') }}" class="mt-2 inline-block">Create Group</x-link>
            </x-card>
            <x-card>
                <p class="text-sm text-cerulean-800">Have an invite and want to join?</p>
                <x-link href="{{ url('/groups/join') }}" class="mt-2 inline-block">Join Group</x-link>
            </x-card>
        </div>

        <div class="mt-4 space-y-3">
            @forelse(($groups ?? []) as $group)
                @php
                    $groupId = data_get($group, 'id');
                    $name = data_get($group, 'name', 'Untitled Group');
                    $role = strtoupper((string) (data_get($group, 'pivot.role') ?? data_get($group, 'membership.role') ?? data_get($group, 'role') ?? 'MEMBER'));
                    $avatar = data_get($group, 'avatar_url') ?? data_get($group, 'avatar') ?? null;
                    $membersCount = data_get($group, 'members_count');
                    $lastActivity = data_get($group, 'last_activity_at') ?? data_get($group, 'updated_at');
                @endphp

                <x-card>
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="h-12 w-12 overflow-hidden rounded-2xl bg-cerulean-100 text-cerulean-700 flex items-center justify-center font-semibold">
                                @if($avatar)
                                    <img src="{{ $avatar }}" alt="{{ $name }} avatar" class="h-full w-full object-cover">
                                @else
                                    {{ \Illuminate\Support\Str::substr($name, 0, 1) }}
                                @endif
                            </div>

                            <div>
                                <h2 class="text-base font-semibold text-cerulean-900">{{ $name }}</h2>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-cerulean-700">
                                    <span class="rounded-xl bg-cerulean-100 px-2 py-1">Role: {{ $role }}</span>
                                    @if(!is_null($membersCount))
                                        <span class="rounded-xl bg-cerulean-50 px-2 py-1">{{ $membersCount }} members</span>
                                    @endif
                                    @if($lastActivity)
                                        <span class="rounded-xl bg-cerulean-50 px-2 py-1">Last activity: {{ $lastActivity }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <x-link :href="$groupId ? url('/groups/'.$groupId) : '#'" class="whitespace-nowrap text-sm">
                            Open Group
                        </x-link>
                    </div>
                </x-card>
            @empty
                <x-card class="text-center py-10">
                    <h2 class="text-lg font-semibold text-cerulean-900">No groups yet</h2>
                    <p class="mt-2 text-sm text-cerulean-700">Create your first group or join an existing one.</p>
                    <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:justify-center">
                        <x-link href="{{ url('/groups/create') }}" class="inline-flex items-center justify-center rounded-2xl border border-cerulean-300 px-4 py-2">
                            Create Group
                        </x-link>
                        <x-link href="{{ url('/groups/join') }}" class="inline-flex items-center justify-center rounded-2xl border border-cerulean-300 px-4 py-2">
                            Join Group
                        </x-link>
                    </div>
                </x-card>
            @endforelse
        </div>
    </div>
@endsection
