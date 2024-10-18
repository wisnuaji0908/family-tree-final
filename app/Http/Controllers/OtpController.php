<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public function sendOtp(Request $request) {
        $request->validate([
            'phone_number'=>'required|string|min:10|max:15',
        ]);

        $otpCode = rand(100000, 99999);
        
        Otp::updateOrCreate(
            ['phone_number'=>$request->phone_number],
            ['otp_code'=>$otpCode, 'expires_at'=>Carbon::now()->addMinutes(5)]
        );

        $apiResponse = Http::baseUrl('https://app.japati.id')
            ->withToken('API-TOKEN-fnG7nPGvCXVhuluKPxoyqj0YNKT8jAb2QnmWYyQBMQeJrbdnPps7l7')
            ->post('/api/send-message', [
                'gateway' => '6282128208361',
                'number' => $request->phone_number,
                'type' => 'text',
                'message' => '*' . $otpCode . '* adalah kode OTP Anda. Demi keamanan, jangan bagikan kode ini.',
            ]);

            logger($apiResponse->json());

            if ($apiResponse->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor WhatsApp tidak ditemukan.',
                ], 500);
            }
    }

    public function verifyOtp(Request $request){
        $request->validate([
            'phone_number' => 'required|numeric',
            'otp_code' => 'required|numeric',
        ]);

        $otp = Otp::where('phone_number', $request->phone_number)
                ->where('otp_code', $request->otp_code)
                ->where('expires_at', '>', Carbon::now())
                ->first();

                if (!$otp) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode OTP tidak valid atau sudah kadaluarsa.',
                    ]);
                }
    }
}
