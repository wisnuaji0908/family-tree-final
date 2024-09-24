<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
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


        $emailExists = User::where('email', $request->email)->exists();

    if ($emailExists) {
        return back()->withErrors([
            'password' => 'The password is incorrect.',
        ])->onlyInput('password')->withInput($request->only('email'));
    } else {
        return back()->withErrors([
            'email' => 'The Email do not match our records.',
        ])->onlyInput('email')->withInput($request->only('email'));
    }


    }
}
