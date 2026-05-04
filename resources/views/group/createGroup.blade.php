@php($title = 'Create Group')

@extends('layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-5xl">
        <x-heading title="Create Group">
            <x-slot:action>
                <x-link href="{{ url('/groups') }}" class="text-sm">Back to My Groups</x-link>
            </x-slot:action>
        </x-heading>

        <div class="mt-4 flex flex-col gap-4 lg:grid lg:grid-cols-3">
            <x-card class="order-1 bg-gradient-to-br from-cerulean-50 via-white to-cerulean-100 border-cerulean-100 shadow-md lg:col-span-2">
                <div class="mb-4 flex flex-wrap items-center gap-3">
                    <span class="inline-flex h-9 items-center rounded-2xl border border-cerulean-200 bg-white px-3 text-xs font-semibold text-cerulean-700">
                        Step 1 · Group Details
                    </span>
                    <p class="text-sm text-cerulean-800">Create a space and you will be set as <span class="font-semibold">OWNER</span>.</p>
                </div>

                <form method="POST" action="{{ url('/groups') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <x-form.input
                        name="name"
                        label="Group Name"
                        placeholder="Ex: Apartment Expenses"
                        required
                    />

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-dashed border-cerulean-300 bg-white/80 p-4">
                            <label for="avatar" class="mb-2 block text-xs font-medium text-cerulean-800">
                                Group Avatar (optional)
                            </label>
                            <input
                                id="avatar"
                                name="avatar"
                                type="file"
                                accept="image/*"
                                class="block w-full rounded-2xl border border-cerulean-200 bg-white px-4 py-2 text-sm outline-none focus:border-cerulean-600 focus:ring-4 focus:ring-cerulean-100 file:mr-3 file:rounded-xl file:border-0 file:bg-cerulean-100 file:px-3 file:py-2 file:text-cerulean-800 hover:file:bg-cerulean-200"
                            />
                            <p class="mt-2 text-xs text-cerulean-700">PNG/JPG recommended</p>
                            <x-form.error name="avatar" />
                        </div>

                        <x-form.textarea
                            name="description"
                            label="Description (optional)"
                            placeholder="What is this group for?"
                            rows="5"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <x-button type="submit" variant="primary">Create Group</x-button>
                        <x-link href="{{ url('/groups') }}" class="inline-flex h-14 w-full items-center justify-center rounded-2xl border border-cerulean-300 text-cerulean-800 hover:bg-cerulean-50">
                            Cancel
                        </x-link>
                    </div>
                </form>
            </x-card>

            <div class="order-2 space-y-4">
                <x-card class="bg-white/90">
                    <h3 class="text-sm font-semibold text-cerulean-900">Quick Tips</h3>
                    <ul class="mt-3 space-y-2 text-sm text-cerulean-800">
                        <li>• Keep the name short and clear.</li>
                        <li>• Add an avatar so teammates recognize it quickly.</li>
                        <li>• Describe what expenses this group tracks.</li>
                    </ul>
                </x-card>

                <x-card class="bg-cerulean-700 text-white">
                    <p class="text-sm font-semibold">Ownership is automatic</p>
                    <p class="mt-1 text-xs text-white/85">After submit, your membership should be created with role OWNER.</p>
                </x-card>
            </div>
        </div>

        <x-card class="mt-4">
            <p class="text-sm text-cerulean-700">
                Mobile-first behavior: content stacks on small screens and becomes a 2+1 grid from large screens.
            </p>
        </x-card>
    </div>
@endsection
