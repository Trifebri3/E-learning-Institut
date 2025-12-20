<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Program;
use App\Models\Kelas;
use App\Models\Submission;
use App\Models\EssaySubmission;

class DashboardControllerIN extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil ID semua program yang diajar oleh instruktur ini
        // Menggunakan relasi 'instructedPrograms' yang baru kita buat di Model User
        $programIds = $user->instructedPrograms()->pluck('programs.id');

        // 2. HITUNG STATISTIK UTAMA
        $stats = [
            // Jumlah Program yang diampu
            'total_programs' => $programIds->count(),

            // Total Siswa (Unik) yang ada di program-program tersebut
            'total_students' => User::whereHas('programs', function($q) use ($programIds) {
                $q->whereIn('program_id', $programIds);
            })->count(),

            // Jumlah Kelas Aktif
            'total_classes' => Kelas::whereIn('program_id', $programIds)
                ->where('is_published', true)
                ->count(),

            // Total Tugas/Esai yang BELUM DINILAI (To-Do List Instruktur)
            'needs_grading' =>
                Submission::whereHas('assignment.kelas', fn($q) => $q->whereIn('program_id', $programIds))
                    ->where('is_graded', false)->count()
                +
                EssaySubmission::whereHas('exam.kelas', fn($q) => $q->whereIn('program_id', $programIds))
                    ->where('status', 'submitted')->count()
        ];

        // 3. DAFTAR PROGRAM SAYA (Untuk Tabel Ringkas)
        $myPrograms = Program::whereIn('id', $programIds)
            ->withCount(['participants', 'kelas'])
            ->latest()
            ->get();

        // 4. FEED AKTIVITAS (Tugas Masuk Terbaru)
        $recentSubmissions = Submission::whereHas('assignment.kelas', fn($q) => $q->whereIn('program_id', $programIds))
            ->with(['user', 'assignment.kelas'])
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('instructor.dashboard', compact('stats', 'myPrograms', 'recentSubmissions'));
    }
}
