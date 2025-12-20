<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTermsAgreed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user belum login, biarkan (ditangani auth middleware)
        if (!$user) {
            return $next($request);
        }

        // Daftar rute yang dikecualikan (agar tidak loop redirect)
        $excludedRoutes = [
            'terms.show',    // Halaman baca syarat
            'terms.accept',  // Aksi setuju
            'profile-data.edit',   // Halaman isi profil (biasanya ini dulu)
            'profile-data.update', // Aksi simpan profil
            'logout',
        ];

        // Cek apakah rute saat ini ada di daftar pengecualian
        if (in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }

        // [LOGIKA UTAMA]
        // Jika kolom 'agreed_to_tos_at' masih KOSONG (NULL)
        if (is_null($user->agreed_to_tos_at)) {
            return redirect()->route('terms.show');
        }

        return $next($request);
    }
}
