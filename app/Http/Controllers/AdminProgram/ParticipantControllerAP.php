<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Program;
use App\Models\NomorInduk;

class ParticipantControllerAP extends Controller
{
    /**
     * Menampilkan daftar peserta dan manajemen nomor induk.
     */
public function index(Request $request)
{
$admin = Auth::user();

// Ambil ID program yang dikelola admin ini
$programIds = $admin->administeredPrograms()->pluck('programs.id');

$participantsQuery = User::whereHas('programs', function ($q) use ($programIds) {
    $q->whereIn('programs.id', $programIds);
})
->with([
    'profile',
    'nomorInduks',
    'programs' => function ($q) use ($programIds) {
        $q->whereIn('programs.id', $programIds);
    }
]);


    // Search
    if ($request->has('search_participant')) {
        $search = $request->search_participant;

        $participantsQuery->where(function ($q) use ($search) {
            $q->where('users.name', 'like', "%$search%")
              ->orWhere('users.email', 'like', "%$search%");
        });
    }

    // Sorting
    if ($request->has('sort_participant') && $request->sort_participant == 'name_asc') {
        $participantsQuery->orderBy('users.name', 'asc');   // SAFE
    } else {
        $participantsQuery->orderBy('users.created_at', 'desc');  // SAFE
    }

    $participants = $participantsQuery->paginate(10, ['*'], 'participants_page');


    // --- TAB 2: MANAJEMEN NOMOR INDUK ---
    $nomorInduksQuery = NomorInduk::with(['user', 'program'])
        ->where(function ($q) use ($programIds) {
            $q->whereIn('nomor_induks.program_id', $programIds)
              ->orWhereNull('nomor_induks.program_id');
        });

    // Search NI
    if ($request->has('search_ni')) {
        $searchNI = $request->search_ni;

        $nomorInduksQuery->where(function ($q) use ($searchNI) {
            $q->where('nomor_induk', 'like', "%$searchNI%")
              ->orWhereHas('user', function ($q2) use ($searchNI) {
                  $q2->where('users.name', 'like', "%$searchNI%");
              });
        });
    }

    $nomorInduks = $nomorInduksQuery->latest()->paginate(10, ['*'], 'ni_page');

    // List Program untuk Dropdown Create NI
    $managedPrograms = $admin->administeredPrograms;

    // List User (calon penerima NI)
    $users = User::where('role', 'participant')->orderBy('users.name')->get();

    return view('adminprogram.participants.index', compact(
        'participants',
        'nomorInduks',
        'managedPrograms',
        'users'
    ));
}


    /**
     * Generate Nomor Induk Baru.
     */
    public function storeNomorInduk(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'custom_code' => 'nullable|string|unique:nomor_induks,nomor_induk|max:50',
        ]);

        // Generate kode otomatis jika kosong: NI-TIMESTAMP-RANDOM
        $code = $request->custom_code ?? 'NI-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));

        NomorInduk::create([
            'user_id' => $request->user_id,
            'nomor_induk' => $code,
            'is_active' => true,
            'program_id' => null, // Belum redeem
        ]);

        return back()->with('success', 'Nomor Induk berhasil dibuat: ' . $code);
    }

    /**
     * Toggle Status Aktif Nomor Induk.
     */
    public function toggleNomorInduk($id)
    {
        $ni = NomorInduk::findOrFail($id);
        $ni->update(['is_active' => !$ni->is_active]);

        $status = $ni->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Nomor Induk berhasil $status.");
    }

    /**
     * Non-aktifkan Peserta dari Program (Kick/Drop Out).
     * Caranya: Detach dari program_user dan kosongkan program_id di nomor_induk
     */
    public function deactivateParticipant(Request $request, $userId)
    {
        $request->validate(['program_id' => 'required|exists:programs,id']);

        $user = User::findOrFail($userId);
        $programId = $request->program_id;

        // 1. Hapus dari tabel pivot (Un-enroll)
        $user->programs()->detach($programId);

        // 2. Reset Nomor Induk yang dipakai (opsional, agar bisa dipakai lagi atau dimatikan)
        // Cari NI yang dipakai user ini untuk program ini
        $ni = NomorInduk::where('user_id', $userId)->where('program_id', $programId)->first();
        if ($ni) {
            // Opsi A: Hapus permanen
            // $ni->delete();

            // Opsi B: Reset jadi null (bisa redeem lagi)
            // $ni->update(['program_id' => null]);

            // Opsi C: Matikan (Soft Ban) - Kita pakai ini agar history terjaga
            $ni->update(['is_active' => false]);
        }

        return back()->with('success', 'Peserta berhasil dinonaktifkan dari program.');
    }

    /**
     * Halaman Cetak PDF (Print Friendly).
     */
    public function printPdf(Request $request)
    {
        $admin = Auth::user();
        $programIds = $admin->administeredPrograms()->pluck('programs.id');

        // Jika ada filter program tertentu
        if ($request->has('program_id') && in_array($request->program_id, $programIds->toArray())) {
             $selectedProgram = Program::find($request->program_id);
             $title = "Laporan Peserta - " . $selectedProgram->title;

             $participants = User::whereHas('programs', function ($q) use ($request) {
                $q->where('program_id', $request->program_id);
             })->with(['profile', 'nomorInduks' => function($q) use ($request) {
                 $q->where('program_id', $request->program_id);
             }])->get();

        } else {
             $title = "Laporan Semua Peserta Program";
             // Ambil semua
             $participants = User::whereHas('programs', function ($q) use ($programIds) {
                $q->whereIn('program_id', $programIds);
             })->with(['profile', 'nomorInduks'])->get();
        }

        return view('adminprogram.participants.print', compact('participants', 'title'));
    }
}
