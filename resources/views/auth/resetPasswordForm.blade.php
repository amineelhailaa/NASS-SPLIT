@php($title = 'Reset Password - NASS SPLIT')

@extends('layouts.guest')

@section('content')
    <div class="w-full max-w-md rounded-2xl bg-gray-50 border border-gray-200 p-4 mx-2">
        <div class="py-4 flex justify-center">
            <a href="/">
                <img src="{{ asset('images/nass_split_logo.png') }}"
                     alt="logo" width="100" height="100" loading="lazy" />
            </a>
        </div>

        <h1 class="mb-2 text-center text-3xl font-semibold font-cash2 text-cerulean-700">Reset Password</h1>
        <p class="mb-6 text-center text-sm text-cerulean-700 font-bold">Enter your new password.</p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <x-form.input label="Email" name="email" type="email" id="email"
                              placeholder="name@example.com" autocomplete="email"
                              :value="$email" />
            </div>

            <div class="mb-4">
                <x-form.input label="New Password" name="password" type="password" id="password"
                              placeholder="New password" autocomplete="new-password" />
            </div>

            <div class="mb-4">
                <x-form.input label="Confirm Password" name="password_confirmation" type="password"
                              id="password_confirmation" placeholder="Confirm password"
                              autocomplete="new-password" />
            </div>

            <x-button variant="primary" >Reset Password</x-button>
        </form>
    </div>
@endsection
