<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Program;
use App\Models\Kelas;
use App\Models\SupportTicket;
use App\Models\Submission;
use App\Models\PresensiHasil;
use Carbon\Carbon;

class DashboardControllerAS extends Controller
{
    /**
     * Menampilkan Dashboard Utama Super Admin.
     */
    public function index()
    {
        // 1. Statistik Utama (Kartu Atas)
        $stats = [
            'total_participants' => User::where('role', 'participant')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_programs' => Program::count(),
            'active_classes' => Kelas::where('is_published', true)->count(),
            'pending_tickets' => SupportTicket::where('status', 'open')->count(),
            'total_submissions' => Submission::count(),
        ];

        // 2. Grafik Pendaftaran Peserta (7 Hari Terakhir)
        // Kita ambil data user participant yang mendaftar 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = Carbon::now()->subDays($i)->format('d M');
            $chartData['data'][] = User::where('role', 'participant')
                                       ->whereDate('created_at', $date)
                                       ->count();
        }

        // 3. Aktivitas Terbaru (User Baru Bergabung)
        $recentUsers = User::where('role', 'participant')
                           ->with('profile')
                           ->latest()
                           ->take(5)
                           ->get();

        // 4. Tiket Bantuan Terbaru (Butuh Perhatian)
        $recentTickets = SupportTicket::with('user')
                                      ->where('status', 'open') // Hanya yang open
                                      ->latest()
                                      ->take(5)
                                      ->get();

        return view('superadmin.dashboard', compact('stats', 'chartData', 'recentUsers', 'recentTickets'));
    }
}
