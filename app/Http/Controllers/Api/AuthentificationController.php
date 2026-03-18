<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthentificationController extends Controller
{
    //
    public function index(){
        return view('auth.login');
    }

    public function login(LoginRequest $request){
        Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember());
        $request->session()->regenerate();
        return redirect()->intended(route('home'));
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
