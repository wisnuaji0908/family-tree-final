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
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        // Temukan pengaturan berdasarkan ID
        $setting = Setting::findOrFail($id);
        
        // Update nama aplikasi
        $setting->app_name = $request->input('app_name');

        // Cek jika ada logo yang diupload
        if ($request->hasFile('app_logo')) {
            // Hapus logo lama jika ada
            if ($setting->app_logo) {
                try {
                    Storage::disk('public')->delete($setting->app_logo);
                } catch (\Exception $e) {
                    // Tambahkan penanganan kesalahan jika perlu
                    return redirect()->back()->withErrors('Error deleting old logo: ' . $e->getMessage());
                }
            }
            
            // Simpan logo baru
            $logoPath = $request->file('app_logo')->store('logos', 'public');
            $setting->app_logo = $logoPath;
        }

        // Simpan perubahan
        $setting->save();

        // Simpan pesan sukses di session
        session()->flash('success', 'Settings updated successfully.');

        // Redirect ke index admin
        return redirect()->route('admin.index');
    }   
}
