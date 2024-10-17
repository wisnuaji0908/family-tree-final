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

    public function store(Request $request)
{
    $credentials = $request->validate([
        'phone_number' => ['required', 'digits_between:10,15', 'exists:users,phone_number'],
        'password' => ['required'],
    ]);

    // Attempt to log in using phone_number and password
    if (Auth::attempt(['phone_number' => $credentials['phone_number'], 'password' => $request->password], $request->filled('remember'))) {
        $request->session()->regenerate();
    
        if (auth()->user()->role === 'admin') {
            return redirect()->intended('/admin');
        }
    
        return redirect()->intended('/people');
    }
    

    // Check if the phone number exists in the database
    $phoneExists = User::where('phone_number', $request->phone_number)->exists();

    if ($phoneExists) {
        return back()->withErrors([
            'password' => 'The password is incorrect.',
        ])->withInput($request->only('phone_number'));
    } else {
        return back()->withErrors([
            'phone_number' => 'The phone number does not match our records.',
        ])->withInput($request->only('phone_number'));
    }
}

}
