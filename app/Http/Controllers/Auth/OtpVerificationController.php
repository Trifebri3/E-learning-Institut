<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OtpVerificationController extends Controller
{
    // Menampilkan halaman form OTP
    public function create(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect(route('dashboard'));
        }

        return view('auth.verify-otp');
    }

    // Memvalidasi dan memverifikasi OTP
    public function store(Request $request)
    {
        $request->validate(['otp' => 'required|string|digits:6']);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect(route('dashboard'));
        }

        // Cek OTP
        if ($user->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // Cek Waktu Kedaluwarsa
        if (now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.']);
        }

        // Sukses! Verifikasi user
        $user->markEmailAsVerified(); // Mengisi 'email_verified_at'

        // Hapus OTP dari database
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        // Redirect ke dashboard (yang akan ditangani oleh DashboardRedirectController)
        return redirect(route('dashboard'));
    }
}
