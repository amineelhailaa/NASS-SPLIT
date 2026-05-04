@php($title = 'Forgot Password - NASS SPLIT')

@extends('layouts.guest')

@section('content')
    <div class="w-full max-w-md rounded-2xl bg-gray-50 border border-gray-200 p-4 mx-2">
        <div class="py-4 flex justify-center">
            <a href="/">
                <img src="{{asset('storage/images/message_sent.svg')}}"
                     alt="logo" width="100" height="100" loading="lazy" />
            </a>
        </div>
        <h1 class="mb-2 text-center text-2xl font-semibold  font-cash1 text-cerulean-700 ">We've sent you an email with a link to reset your password.</h1>
        <p class="mb-8 text-center text-sm text-cerulean-700 font-normal ">Check your spam and promotions folder if it doesn't appear in your main mailbox.</p>

    </div>
@endsection
