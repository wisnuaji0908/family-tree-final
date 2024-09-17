<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(){

        return view('auth.register');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        User::create([
            ...$validated,
            'role' => 'parents',
        ]);

        // Auth::login($user);

        return to_route('login')->with('success', 'Registration Successfully');
    }
}
