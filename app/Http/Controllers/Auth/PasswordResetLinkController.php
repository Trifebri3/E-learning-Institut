<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // Tetap pakai
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Mail\SendOtpMail;
use App\Rules\Turnstile;


class PasswordResetLinkController extends Controller
{
    /**
     * Display the form.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     * KITA UBAH LOGIKA INI SEPENUHNYA
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'cf-turnstile-response' => ['required', 'string', new Turnstile], // <-- [2] TAMBAHKAN RULE
        ]);


        // Cari user
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ada, kita tetap kirim respon "sukses"
        // Ini untuk keamanan agar orang tidak bisa menebak email terdaftar
        if (!$user) {
            return back()->with('status', 'Jika email Anda terdaftar, kami telah mengirimkan OTP.');
        }

        // Buat dan simpan OTP ke tabel 'users' (seperti saat registrasi)
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10); // OTP berlaku 10 menit
        $user->save();

        // Kirim email OTP
        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            // Tangani jika email gagal terkirim
        }

        // Redirect ke HALAMAN BARU (Verifikasi OTP)
        // Kita "tempelkan" email ke URL agar halaman berikutnya tahu
        return redirect(route('password.verify-otp', ['email' => $request->email]))
               ->with('status', 'Kami telah mengirimkan kode OTP ke email Anda.');
    }
}
