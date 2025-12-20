<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsController extends Controller
{
    /**
     * Tampilkan halaman Syarat & Ketentuan.
     */
    public function show()
    {
        // Jika sudah setuju, langsung lempar ke dashboard
        if (Auth::user()->agreed_to_tos_at) {
            return redirect()->route('dashboard');
        }

        return view('terms');
    }

    /**
     * Proses persetujuan user.
     */
    public function accept(Request $request)
    {
        // Validasi bahwa checkbox dicentang
        $request->validate([
            'agreement' => 'accepted',
        ], [
            'agreement.accepted' => 'Anda wajib menyetujui syarat dan ketentuan untuk melanjutkan.'
        ]);

        $user = Auth::user();

        // Simpan waktu persetujuan
        $user->update([
            'agreed_to_tos_at' => now(),
        ]);

        return redirect()->route('dashboard')
                         ->with('success', 'Selamat datang! Akun Anda telah aktif sepenuhnya.');
    }
}
