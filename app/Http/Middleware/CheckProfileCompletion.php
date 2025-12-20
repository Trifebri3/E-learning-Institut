<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // [INI KUNCINYA]
        // Paksa muat ulang relasi 'profile' dari DATABASE
        $user->load('profile');

        // Buat profile kosong jika memang belum ada
        if (!$user->profile) {
            \App\Models\Profile::create(['user_id' => $user->id]);
            $user->load('profile'); // Muat ulang relasi
        }

        // Daftar rute yang diizinkan diakses
        $allowedRoutes = [
            'profile-data.edit',
            'profile-data.update',
            'logout',
        ];

        // Cek data yang sudah 'fresh'
        if (!$user->profile->is_complete && !in_array($request->route()->getName(), $allowedRoutes)) {
            return redirect()->route('profile-data.edit')
                ->with('warning', 'Harap lengkapi Data Diri Anda untuk melanjutkan.');
        }

        return $next($request);
    }
}
