<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function index(){
        return view('auth.forgetPassword');
    }

    public function resetPassword(Request $request){
        $request->validate(['email' => 'required|email']);
        $situation = Password::sendResetLink($request->only('email'));

        if ($situation === Password::RESET_LINK_SENT) {
            return view('auth.checkMail');
        }

        return back()
            ->withInput($request->only('email')) //to keep the email like php whenn we used to fill errors in data
            ->withErrors(['email' => __($situation)]);//make it human
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $token) //this function when the user open the link
    {
        return view('auth.resetPasswordForm', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|confirmed|min:8',
            'token'=>'required',
        ]);
       Password::reset($request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password){
            $user->password = Hash::make($password);
            $user->save();
            });
       return redirect()->route('login');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
