<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\PresensiHasil;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\Quiz;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar SEMUA kelas dari SEMUA program user.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Program yang diikuti user
        $programs = $user->programs ?? collect();

        // Load semua kelas + resources-nya (HANYA yang published)
        $kelasQuery = Kelas::whereIn('program_id', $programs->pluck('id'))
            ->where('is_published', true) // HANYA kelas yang published
            ->with(['program', 'presensiSetup', 'presensiHasil', 'resources', 'videoEmbeds']);

        // Ambil semua kelas (collection)
        $allKelas = $kelasQuery->get();

        // Hitung statistik
        $totalKelas = $allKelas->count();
        $totalFinished = $allKelas->filter(fn($item) => $item->isFinished())->count();
        $totalOngoing = $totalKelas - $totalFinished;
        $totalPrograms = $programs->count();

        $progressPercentage = $totalKelas > 0
            ? round(($totalFinished / $totalKelas) * 100)
            : 0;

        // Filter & Sorting
        $filter = $request->get('filter', 'all');
        $sort = $request->get('sort', 'nearest');
        $viewType = $request->get('view', 'grid');

        $kelas = $allKelas;

        // Filter
        if ($filter === 'ongoing') {
            $kelas = $kelas->filter(fn($item) => !$item->isFinished());
        } elseif ($filter === 'finished') {
            $kelas = $kelas->filter(fn($item) => $item->isFinished());
        }

        // Sorting
        if ($sort === 'asc') {
            $kelas = $kelas->sortBy('tanggal');
        } elseif ($sort === 'desc') {
            $kelas = $kelas->sortByDesc('tanggal');
        } else { // nearest
            $kelas = $kelas->sortBy(fn($item) => $item->tanggal);
        }

        // Kelas terdekat
        $kelasTerdekat = $allKelas
            ->filter(fn($item) => !$item->isFinished())
            ->sortBy('tanggal')
            ->take(3);

        // Kirim semua resources (gabungan per kelas)
        $resources = $allKelas->pluck('resources')->flatten();
        $essayExams = $allKelas->pluck('essayExams')->flatten();

        return view('participant.kelas.index', compact(
            'kelas',
            'resources',
            'kelasTerdekat',
            'totalKelas',
            'totalFinished',
            'totalOngoing',
            'totalPrograms',
            'progressPercentage',
            'viewType',
            'filter',
            'sort',
            'essayExams'
        ));
    }

    /**
     * Tampilkan detail satu kelas.
     * (Keamanan sudah diperbarui untuk multi-program)
     */
public function show($id)
{
    $user = Auth::user();
    $now = Carbon::now();

    // 1. Ambil daftar ID dari SEMUA program yang user ikuti
    $userProgramIds = $user->programs()->pluck('programs.id');

    // 2. Cari kelas dengan syarat:
    //    - Kelas itu sudah publish (is_published = true)
    //    - DAN program_id dari kelas itu HARUS ADA di daftar program user
    $kelas = Kelas::where('id', $id)
        ->with('modules', 'learningPath.sections', 'videoEmbeds')
        ->where('is_published', true) // HANYA yang published
        ->whereIn('program_id', $userProgramIds)
        ->firstOrFail(); // Gagal 404 jika user tidak terdaftar atau kelas tidak published

    // 3. Ambil quizzes setelah $kelas didefinisikan
    $quizzes = $kelas->quizzes;

    // --- Logika Presensi ---
    $setupPresensi = $kelas->presensiSetup;

    // Ambil hasil presensi user untuk kelas ini
    $hasilPresensi = $user->presensiHasil()->where('kelas_id', $kelas->id)->first();

    $awal_open = false;
    $akhir_open = false;
    if ($setupPresensi && $setupPresensi->is_active) {
        $awal_open = $now->between($setupPresensi->buka_awal, $setupPresensi->tutup_awal);
        $akhir_open = $now->between($setupPresensi->buka_akhir, $setupPresensi->tutup_akhir);
    }

    // Assignments yang published
    $assignments = $kelas->assignments()
        ->where('is_published', true)
        ->with(['submissions' => fn($q) => $q->where('user_id', $user->id)])
        ->get();

    return view('participant.kelas.show', compact(
        'kelas',
        'quizzes', // Tambahkan quizzes di sini
        'setupPresensi',
        'hasilPresensi',
        'awal_open',
        'akhir_open',
        'assignments'
    ));
}
}
