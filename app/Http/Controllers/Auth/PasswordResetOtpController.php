<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetOtpController extends Controller
{
    /**
     * Tampilkan form verifikasi OTP.
     */
    public function create(Request $request)
    {
        // Ambil email dari URL
        return view('auth.verify-reset-otp', ['email' => $request->email]);
    }

    /**
     * Validasi OTP.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // Cek jika user ada, OTP cocok, dan tidak kedaluwarsa
        if (!$user || $user->otp !== $request->otp || now()->gt($user->otp_expires_at)) {
            return back()->withInput()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
        }

        // --- SUKSES! ---
        // OTP benar. Sekarang kita buat "Token" reset password Bawaan Breeze
        // agar bisa menggunakan halaman reset password standarnya.

        // Hapus OTP
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        // Buat token reset password yang asli
        $token = app('auth.password.broker')->createToken($user);

        // Redirect ke halaman reset password STANDAR, dengan membawa token
        return redirect(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));
    }
}
