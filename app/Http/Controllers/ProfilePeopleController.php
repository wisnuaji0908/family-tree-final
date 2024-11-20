<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\People;
use App\Models\Otp;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProfilePeopleController extends Controller
{
    public function profile(){

        $setting = Setting::first();
        $user = auth()->user();
        $people = People::where('user_id', $user->id)->first();

        if (!$people) {
            $people = $user->profile;
        }
        
        return view('profilepeople.index', compact('setting', 'user', 'people'));
    }

    public function profileUpdate(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|numeric',
        'born' => 'required|date',
        'gender' => 'required|in:male,female',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validate the photo
    ]);

    $user = Auth::user();
    
    $user->update([
        'phone_number' => $request->phone,
    ]);

    
    $profileData = [
        'name' => $request->name,
        'birth_date' => $request->born,
        'place_birth' => $request->place_birth,
        'gender' => $request->gender,
    ];

    $people = People::where('user_id', $user->id)->first();

    if ($people) {
        $people->update($profileData);
        $profile = $people->refresh();
        if ($request->hasFile('photo')) {
            if ($profile && $profile->photo_profile && Storage::exists('public/' . $profile->photo_profile)) {
                Storage::delete('public/' . $profile->photo_profile);
            }
    
            $profileData['photo_profile'] = $request->file('photo')->store('profiles', 'public');
        }
    } else {
        $user->profile->update($profileData);

        $profile = $user->profile->refresh();

        if ($request->hasFile('photo')) {
            if ($profile && $profile->photo_profile && Storage::exists('public/' . $profile->photo_profile)) {
                Storage::delete('public/' . $profile->photo_profile);
            }
    
            $profileData['photo_profile'] = $request->file('photo')->store('profiles', 'public');
        }
    }


    // $profile = People::where('user_id', $user->id)->first();

    // $profile = People::update($profileData);

    // $profile = People::updateOrCreate([
    //     'user_id' => $user->id,
    // ], $profileData);

    return redirect()->route('landing.profile.people')->with('success', 'Profile updated successfully');
    }
    
    public function updatePassword(Request $request){
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required | string | min:3 | confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('landing.change.people')->with('warning', 'Current password does not match.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $setting = Setting::first();
        $message = "Hello, " . $user->name . ".\nYour password has been changed successfully. If you didn't make this change, please contact us immediately.";
        $api = Http::baseUrl('https://app.japati.id')
        ->withToken('API-TOKEN-0RxRG4eYZzWHSbH4Z7u570dgtxoxANyLUVfm4JC3Tu7SNrf083yBrx')
        ->post('/api/send-message', [
            'gateway' => '6282130657304',
            'number' => $user->phone_number,
            'type' => 'text',
            'message' => $message,
        ]);

        return redirect()->route('landing.profile.people')->with('success', 'Password updated successfully.');
    }

    public function changePass(){

        $setting = Setting::first();
        $user = auth()->user();
        $people = People::where('user_id', $user->id)->first();$setting = Setting::first();

        return view('profilepeople.changepass', compact('setting', 'user', 'people'));

    }

    public function changePhone(Request $request){
        $phone = $request->validate([
            'phone' => 'required|string|max:13',
        ]);

        $otp = rand(100000, 999999);
        $setting = Setting::first();

        Otp::updateOrCreate([
            'phone_number' => $request->phone,
        ], [
            'phone_number' => $request->phone,
            'otp_code' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        $api = Http::baseUrl('https://app.japati.id')
            ->withToken('API-TOKEN-0RxRG4eYZzWHSbH4Z7u570dgtxoxANyLUVfm4JC3Tu7SNrf083yBrx')
            ->post('/api/send-message', [
                'gateway' => '6282130657304',
                'number' => $request->phone,
                'type' => 'text',
                'message' => '' . $otp. ' is your ' .$setting->app_name. ' Verivication code.',
            ]);

        if (!$api->ok()) {
            dd($api->json());
        }

        session(['phone' => $request->phone]);

        return redirect()->route('validate-otp-customer')->with('send', 'OTP send successfully');
    }

    public function editProfile(){

        $setting = Setting::first();
        $user = auth()->user(); 
        $people = People::where('user_id', $user->id)->first();

        if (!$people) {
            $people = $user->profile;
        }

        return view('profilepeople.editprofile', compact('setting', 'user', 'people'));
    }

    public function editPhone(){

        $setting = Setting::first();
        
        return view('profilepeople.changephone', compact('setting'));
    }

    public function showOtpForm(){

        $setting = Setting::first();
        
        return view('profilepeople.showotp', compact('setting'));
    }

    public function showValidateOtpCustomer(Request $request){
        
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);
    
        $user = Auth::user();
        $otp = $request->otp;
        $phone = session('phone');
        $codeOtp = Otp::where('phone_number', $phone)->first();
        $setting = Setting::first();
    
        if ($codeOtp && $codeOtp->otp_code == $otp) {
            $codeOtp->delete();
    
            $message = "Hello, " . $user->name . ".\nYour " . $setting->app_name . " account is now connected with " . $phone . " number. If you didn't make this change, please contact us immediately.";
            $api = Http::baseUrl('https://app.japati.id')
                ->withToken('API-TOKEN-0RxRG4eYZzWHSbH4Z7u570dgtxoxANyLUVfm4JC3Tu7SNrf083yBrx')
                ->post('/api/send-message', [
                    'gateway' => '6282130657304',
                    'number' => $phone,
                    'type' => 'text',
                    'message' => $message,
                ]);
    
            $user->update([
                'phone_number' => $phone,
                'phone_verified_at' => now(),
            ]);
    
            return redirect()->route('landing.profile.people')->with('success', 'Phone number updated successfully!');
        } else {
            return redirect()->back()->with('invalid-otp', 'Invalid OTP');
        }
    
    }

    public function photo(){

        $people = Auth::user()->people()->first();

        return view('nav', compact('people'));
    }
}
