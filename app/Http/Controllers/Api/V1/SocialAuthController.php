<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    use ApiResponses;
    public function redirectToProvider($provider)
    {
        return $this->successResponse([
            'url' => Socialite::driver($provider)
            ->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function handleCallback($provider){
        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();
        } catch (\Exception $exception){
            return $this->errorResponse('auth failed',401);
        }
        $user = User::updateOrCreate([
            //find array ( include in the second for create )
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName() ?? $socialUser->getNickname(),
            'provider_id' => $socialUser->getId(),
            'provider_name' => $provider,
            // password stay null
        ]);

        $tokenData = $this->createApiToken($user);
        return $this
            ->successResponse($tokenData, "Logged in via {$provider}!");

    }

}
