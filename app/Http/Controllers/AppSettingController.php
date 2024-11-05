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
            Setting::create([
                'app_name' => config('app.name'),
                'japati_token' => null,
                'japati_gateway' => null,
                'japati_url' => null,
            ]);
        }

        return view('setting.index', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'japati_token' => 'nullable|string|max:255',
            'japati_gateway' => 'nullable|string|max:255',
            'japati_url' => 'nullable|string|max:255',
        ]);

        $setting = Setting::findOrFail($id);
        $setting->app_name = $request->input('app_name');
        $setting->japati_token = $request->input('japati_token');
        $setting->japati_gateway = $request->input('japati_gateway');
        $setting->japati_url = $request->input('japati_url');

        if ($request->hasFile('app_logo')) {
            if ($setting?->app_logo) {
                try {
                    Storage::disk('public')->delete($setting?->app_logo);
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
