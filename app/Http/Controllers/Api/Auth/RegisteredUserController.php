<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(SignUpRequest $request): Response
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('', 'public');

            $user->avatar()->create([
                'path' => $path,
                'file_type' => $file->isImage() ? 'image' : 'file',
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
