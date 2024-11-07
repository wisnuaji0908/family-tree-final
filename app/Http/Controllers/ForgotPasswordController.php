<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    // Fungsi untuk menangani permintaan forgot password (berdasarkan nomor whatsapp)
    public function forgotPassword(Request $request)
    {
        \Log::info('Memulai forgotPassword.');

        // Validasi input yang diterima
        $request->validate([
            'identifier' => 'required',
        ]);

        $identifier = $request->input('identifier');
        \Log::info('Identifier yang dimasukkan: ' . $identifier);

        // Jika input adalah nomor telepon
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            \Log::info('Identifier adalah nomor telepon.');

            // Cari customer berdasarkan nomor telepon
            $customer = User::where('phone_number', $identifier)->first();
            if (!$customer) {
                \Log::error('Nomor telepon tidak ditemukan.');
                return back()->withErrors(['identifier' => 'Nomor telepon tidak ditemukan.'])
                ->withInput($request->only('identifier'));
            }

            \Log::info('User ditemukan: ' . $customer->phone_number);

            // Generate OTP (misalnya 6 digit angka acak)
            $otp = rand(100000, 999999);
            \Log::info('OTP yang dihasilkan: ' . $otp);

            // Simpan OTP di tabel password_reset_tokens dengan phone_number
            DB::table('password_reset_tokens')->updateOrInsert(
                ['phone_number' => $customer->phone_number],
                [
                    'token' => $otp,
                    'created_at' => now(),
                    'email' => null,
                ]
            );
            \Log::info('OTP berhasil disimpan.');

            // Kirim OTP ke nomor telepon customer menggunakan API
            $apiResponse = Http::baseUrl('https://app.japati.id')
            ->withToken('API-TOKEN-0RxRG4eYZzWHSbH4Z7u570dgtxoxANyLUVfm4JC3Tu7SNrf083yBrx')
            ->post('/api/send-message', [
                'gateway' => '6282130657304',
                'number' => $request->identifier,
                'type' => 'text',
                'message' => '*' . $otp . '* is your OTP code. For security reasons, do not share this code.',
            ]);

            if ($apiResponse->failed()) {
                \Log::error($apiResponse->json());
                \Log::error('Gagal mengirim OTP ke nomor telepon: ' . $customer->phone_number);
                return back()->withErrors(['identifier' => 'Failed to send the OTP.'])->withInput($request->only('identifier'));
            }

            \Log::info("OTP telah dikirim ke nomor telepon: " . $customer->phone_number);

            // Simpan nomor telepon di session untuk digunakan di halaman verifikasi
            $request->session()->put('identifier', $identifier);

            // Redirect ke halaman verifikasi OTP dengan membawa identifier (nomor telepon)
            return redirect()->route('otp.verify')->with('identifier', $identifier)->withInput($request->only('identifier'));
        }

        \Log::error('Identifier bukan nomor telepon.');
        return back()->withErrors(['identifier' => 'Hanya nomor telepon yang didukung untuk reset password.']);
    }

    // Fungsi untuk menangani proses reset password (berdasarkan nomor whatsapp)
    public function resetPassword(Request $request)
    {
        \Log::info('Memulai resetPassword.');

        // Validasi input dari form reset password
        $request->validate([
            'identifier' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        \Log::info('Identifier: ' . $request->input('identifier'));

        // Temukan customer berdasarkan nomor telepon
        $customer = User::where('phone_number', $request->input('identifier'))->first();

        if (!$customer) {
            \Log::info("Customer tidak ditemukan dengan nomor telepon: " . $request->input('identifier'));
            return back()->withErrors(['identifier' => 'Nomor telepon tidak ditemukan.'])
             ->withInput($request->only('identifier'));
        }

        // Update password customer
        $customer->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->setRememberToken(Str::random(60));

        // Simpan perubahan ke database
        if (!$customer->save()) {
            \Log::error("Gagal menyimpan password untuk customer: " . $customer->phone_number);
            return back()->withErrors(['general' => 'Gagal menyimpan password, silakan coba lagi.']);
        }

        \Log::info("Password berhasil disimpan untuk customer: " . $customer->phone_number);

        // Hapus OTP setelah digunakan jika ada logika untuk itu, atau proses lainnya

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Password Anda telah berhasil di-reset.');
    }

    public function verifyOtp(Request $request)
    {
        \Log::info('Memulai verifikasi OTP.');

        // Validasi input OTP
        $request->validate([
            'identifier' => 'required',
            'otp' => 'required|array|min:6|max:6', // Pastikan input OTP berupa array dengan panjang 6
            'otp.*' => 'numeric|min:0|max:9', // Pastikan setiap karakter OTP adalah angka
        ]);

        // Cari token berdasarkan nomor telepon
        $tokenData = DB::table('password_reset_tokens')
            ->where('phone_number', $request->input('identifier'))
            ->first();

        if (!$tokenData) {
            \Log::info("Token tidak ditemukan untuk nomor telepon: {$request->input('identifier')}");
            return back()->withErrors(['identifier' => 'Nomor telepon tidak terdaftar.']);
        }

        // Gabungkan input OTP menjadi string
        $inputOtp = implode('', $request->input('otp'));

        // Periksa apakah OTP valid
        if ($inputOtp != $tokenData->token) {
            \Log::info("OTP tidak valid.");
            return back()->withErrors(['otp' => 'OTP tidak valid.'])->withInput($request->only('identifier'));
        }

        \Log::info("OTP valid, melanjutkan proses reset password.");

        // Redirect ke halaman reset password setelah OTP terverifikasi
        return redirect()->route('password.reset', ['token' => $tokenData->token])
                        ->with('identifier', $request->input('identifier'));
    }
}
