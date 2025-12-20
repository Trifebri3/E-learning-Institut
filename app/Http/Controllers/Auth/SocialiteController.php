<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // 1. Mengarahkan user ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Menerima callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari atau buat user baru
            $user = User::firstOrCreate(
                [
                    'email' => $googleUser->getEmail() // Cari berdasarkan email
                ],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'role' => 'participant', // <-- DEFAULT ROLE UNTUK USER GOOGLE
                    'password' => null, // <-- Tidak ada password
                    'email_verified_at' => now(), // <-- LANGSUNG VERIFIKASI!
                ]
            );

            // Jika user sudah ada tapi belum ada google_id, update
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Login-kan user
            Auth::login($user, true); // 'true' untuk "Remember Me"

            // Redirect ke dashboard (akan ditangani oleh Redirector kita)
            return redirect(route('dashboard'));

        } catch (\Exception $e) {
            // Tangani error, misal: kembali ke login
            return redirect(route('login'))->withErrors(['email' => 'Gagal login dengan Google.']);
        }
    }
}
