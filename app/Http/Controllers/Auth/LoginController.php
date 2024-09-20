<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    

class LoginController extends Controller
{
    public function login(){
        
        return view('auth.login');

    }

    public function store(Request $request){
        $credentials = $request->validate([
            'email'=>['required', 'email'],
            'password'=>['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->role === 'admin') {
                return redirect()->intended('/admin');
            }

                return redirect()->intended('/people');
        }


        return back()->withErrors([
            'email' => 'The Email do not match our records.',
            'password' => 'The password is incorrect.',
        ])->onlyInput('email', 'password');

    }
}
