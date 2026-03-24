<?php

namespace App\Http\Controllers\Api;
use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthentificationController extends Controller
{
    use ApiResponses ;



    public function register(SignUpRequest $request){
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        return $this->createdResponse($this->Token($user),'Sign up successful!');
    }


    public function Token(User $user,$name='Api Token',$days=30,$permissions=[])
    {
        return [
            'token'=>$user->createToken('Api Token'.$user->email,
                $permissions,
                now()->addDays($days))->plainTextToken
        ];
    }

    public function login(LoginUserRequest $loginUserRequest)
    {
        if(!Auth::attempt($loginUserRequest->only('email','password'))){
            return $this->errorResponse('Invalid credentials',401);
        }

        $user = User::firstWhere('email',$loginUserRequest->email);
        return $this->successResponse($this->Token($user),'login passed !');
    }




    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse('Logged out successfully');
    }
}
