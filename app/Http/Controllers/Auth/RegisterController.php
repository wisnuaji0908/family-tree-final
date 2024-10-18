<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(){
        $setting = Setting::first();

        return view('auth.register', compact('setting'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'phone_number' => ['required', 'digits_between:10,15', 'unique:users,phone_number'],
            'otp_code' => 'required|numeric',
            'password' => ['required', 'confirmed', 'min:3'],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        User::create([
            ...$validated,
            'role' => 'people',
        ]);

        // Auth::login($user);

        return to_route('login')
        ->with('success', 'Registration Successfully')
        ->withInput($request->only('phone_number'));
        }
}
