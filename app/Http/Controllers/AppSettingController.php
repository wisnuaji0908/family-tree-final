<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        if (!$setting) {
            return redirect()->back()->with('error', 'Setting not found. Please create a setting first.');
        }
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $setting = Setting::findOrFail($id);
        $setting->app_name = $request->input('app_name');

        if ($request->hasFile('app_logo')) {
            if ($setting->app_logo) {
                try {
                    Storage::disk('public')->delete($setting->app_logo);
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors('Error deleting old logo: ' . $e->getMessage());
                }
            }
            $logoPath = $request->file('app_logo')->store('logos', 'public');
            $setting->app_logo = $logoPath;
        }

        $setting->save();
        session()->flash('success', 'Settings updated successfully.');
        return redirect()->route('admin.index');
    }   
}
