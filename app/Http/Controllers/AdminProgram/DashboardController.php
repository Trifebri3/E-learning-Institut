<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Program;
use App\Models\Kelas;
use App\Models\Submission;
use App\Models\EssaySubmission;
use App\Models\SupportTicket;
use Carbon\Carbon;

class DashboardController extends Controller
{
 public function index()
{
    $user = Auth::user();

    // 1. Ambil ID semua program yang dikelola admin ini
    $programIds = \App\Models\Program::whereIn('id', function($query) use ($user) {
        $query->select('program_id')
              ->from('program_admin') // pivot admin
              ->where('user_id', $user->id);
    })->pluck('id');

    // 2. STATISTIK UTAMA (Cards)
    $totalParticipants = User::whereIn('id', function($query) use ($programIds) {
        $query->select('user_id')
              ->from('program_instructor') // pivot peserta/instruktur
              ->whereIn('program_id', $programIds);
    })->count();

    $stats = [
        'total_programs' => $programIds->count(),
        'total_participants' => $totalParticipants,
        'active_classes' => \App\Models\Kelas::whereIn('program_id', $programIds)
            ->where('is_published', true)
            ->count(),
        'pending_grading' =>
            \App\Models\Submission::whereHas('assignment.kelas', function($q) use ($programIds) {
                $q->whereIn('program_id', $programIds);
            })->where('is_graded', false)->count()
            +
            \App\Models\EssaySubmission::whereHas('exam.kelas', function($q) use ($programIds) {
                $q->whereIn('program_id', $programIds);
            })->where('status', 'submitted')->count(),
    ];

    // 3. GRAFIK PESERTA PER PROGRAM (Top 5 Program Teramai)
    $topPrograms = \App\Models\Program::whereIn('id', $programIds)
        ->withCount('participants') // pastikan relasi participants di Program.php ada
        ->orderByDesc('participants_count')
        ->take(5)
        ->get();

    $chartLabels = $topPrograms->pluck('title')->toArray();
    $chartData = $topPrograms->pluck('participants_count')->toArray();

    // 4. AKTIVITAS TERBARU (Submission Tugas/Esai Terbaru)
    $recentSubmissions = \App\Models\Submission::whereHas('assignment.kelas', function($q) use ($programIds) {
            $q->whereIn('program_id', $programIds);
        })
        ->with(['user', 'assignment'])
        ->latest('submitted_at')
        ->take(5)
        ->get();

    // 5. TIKET BANTUAN (Khusus Program Ini)
    $recentTickets = \App\Models\SupportTicket::whereIn('program_id', $programIds)
        ->where('status', 'open')
        ->with('user')
        ->latest()
        ->take(3)
        ->get();

    return view('adminprogram.dashboard', compact('stats', 'chartLabels', 'chartData', 'recentSubmissions', 'recentTickets'));
}

}
