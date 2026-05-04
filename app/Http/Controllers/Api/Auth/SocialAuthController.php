<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    use ApiResponses;

    public function redirectToProvider($provider)
    {
        return $this->successResponse([
            'url' => Socialite::driver($provider)
                ->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function handleCallback($provider)
    {

        $socialUser = Socialite::driver($provider)
            ->stateless()
            ->user();

        $user = User::updateOrCreate([
            // find array ( include in the second for create )
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName() ?? $socialUser->getNickname(),
            'provider_id' => $socialUser->getId(),
            'provider_name' => $provider,
            // password stay null
        ]);
        Auth::login($user, true);

        request()->session()->regenerate();

        return redirect(config('app.frontend_url').'/groups');

    }
}
