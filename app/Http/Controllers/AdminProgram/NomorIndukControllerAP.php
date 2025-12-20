<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Str;
use App\Models\User;
use App\Models\NomorInduk;


class NomorIndukControllerAP extends Controller
{
    /**
     * Halaman Manajemen Nomor Induk
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $programIds = $admin->administeredPrograms()->pluck('programs.id');

        // Default state
        $candidates   = collect();
        $existingNIs  = collect();
        $isSearched   = false;

        /**
         * FILTER KETAT
         * Query hanya dijalankan jika ada pencarian minat
         */
        if ($request->filled('search_minat')) {
            $isSearched = true;
            $search = $request->search_minat;

            /**
             * ==================================================
             * QUERY 1: KANDIDAT (BELUM PUNYA NOMOR INDUK)
             * ==================================================
             */
            $candidatesQuery = User::where('role', 'participant')

                // Wajib punya profile & data inti lengkap
                ->whereHas('profile', function ($q) use ($search) {
                    $q->whereNotNull('nomor_hp')
                      ->whereNotNull('alamat_lengkap')
                      ->whereNotNull('tanggal_lahir')
                      ->where('minat_program', 'like', "%{$search}%");
                })

                // Belum memiliki NI di program admin ini
                ->whereDoesntHave('nomorInduks', function ($q) use ($programIds) {
                    $q->whereIn('program_id', $programIds)
                      ->orWhereNull('program_id');
                });

            // Sorting
            if ($request->sort === 'oldest') {
                $candidatesQuery->orderBy('created_at', 'asc');
            } else {
                $candidatesQuery->orderBy('created_at', 'desc');
            }

            $candidates = $candidatesQuery
                ->paginate(10, ['*'], 'candidates_page')
                ->withQueryString();

            /**
             * ==================================================
             * QUERY 2: EXISTING NOMOR INDUK (REFERENSI)
             * ==================================================
             */
            $existingNIs = NomorInduk::with(['user.profile'])
                ->whereHas('user.profile', function ($q) use ($search) {
                    $q->where('minat_program', 'like', "%{$search}%");
                })
                ->where(function ($q) use ($programIds) {
                    $q->whereIn('program_id', $programIds)
                      ->orWhereNull('program_id');
                })
                ->latest()
                ->get();
        }

        return view(
            'adminprogram.nomorinduk.index',
            compact('candidates', 'existingNIs', 'isSearched')
        );
    }

    /**
     * Store Nomor Induk Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'custom_code' => 'nullable|string|max:50|unique:nomor_induks,nomor_induk',
        ]);

        // Security layer: validasi ulang data profile
        $user = User::with('profile')->findOrFail($request->user_id);

        if (
            !$user->profile ||
            !$user->profile->nomor_hp ||
            !$user->profile->alamat_lengkap ||
            !$user->profile->tanggal_lahir ||
            !$user->profile->minat_program
        ) {
            return back()->with('error', 'Data peserta belum lengkap.');
        }

        // Generate kode NI
        $code = $request->custom_code
            ?? 'NI-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));

        NomorInduk::create([
            'user_id'     => $user->id,
            'nomor_induk' => $code,
            'is_active'   => true,
            'program_id'  => null,
        ]);

        return back()->with(
            'success',
            'Berhasil! Nomor Induk dibuat untuk ' . $user->name
        );
    }

    /**
     * Toggle Status Aktif / Nonaktif
     */
    public function toggle($id)
    {
        $ni = NomorInduk::findOrFail($id);

        $ni->update([
            'is_active' => !$ni->is_active,
        ]);

        return back()->with('success', 'Status Nomor Induk diperbarui.');
    }
}
