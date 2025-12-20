<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;

class ProgramController extends Controller
{
    /**
     * Menampilkan daftar semua program.
     */

public function index(Request $request)
{
    $user = auth()->user();

    // Ambil semua program dengan relasi dan hitung statistik
    $programs = Program::withCount([
        'kelas',
        'participants',
        'assignments'
    ])->with(['participants' => function($query) use ($user) {
        $query->where('users.id', $user->id);
    }]);

    // Filter berdasarkan status
    if ($request->has('status') && $request->status != '') {
        $now = now();
        switch($request->status) {
            case 'active':
                $programs->where('waktu_mulai', '<=', $now)
                         ->where('waktu_selesai', '>=', $now);
                break;
            case 'upcoming':
                $programs->where('waktu_mulai', '>', $now);
                break;
            case 'completed':
                $programs->where('waktu_selesai', '<', $now);
                break;
            case 'self-paced':
                $programs->whereNull('waktu_mulai')
                         ->orWhereNull('waktu_selesai');
                break;
        }
    }

    // Sorting
    $sort = $request->get('sort', 'latest');
    switch($sort) {
        case 'latest':
            $programs->orderBy('created_at', 'desc');
            break;
        case 'oldest':
            $programs->orderBy('created_at', 'asc');
            break;
        case 'name_asc':
            $programs->orderBy('title', 'asc');
            break;
        case 'name_desc':
            $programs->orderBy('title', 'desc');
            break;
        case 'start_date':
            $programs->orderBy('waktu_mulai', 'desc');
            break;
        case 'end_date':
            $programs->orderBy('waktu_selesai', 'desc');
            break;
        default:
            $programs->orderBy('created_at', 'desc');
    }

    $programs = $programs->paginate(12);

    // Tentukan status dan cek partisipasi untuk setiap program
    foreach ($programs as $program) {
        $program->is_joined = $program->participants->contains($user->id);
        $program->status = $this->getProgramStatus($program);
    }

    // Statistik program
    $now = now();
    $totalPrograms     = Program::count();
    $activePrograms    = Program::where('waktu_mulai', '<=', $now)
                                ->where('waktu_selesai', '>=', $now)
                                ->count();
    $completedPrograms = Program::where('waktu_selesai', '<', $now)->count();
    $upcomingPrograms  = Program::where('waktu_mulai', '>', $now)->count();

    // Program aktif user (hanya yang sedang berjalan)
    $activeUserProgram = $user->programs()
                              ->where('waktu_mulai', '<=', $now)
                              ->where('waktu_selesai', '>=', $now)
                              ->first();
    if ($activeUserProgram) {
        $activeUserProgram->is_joined = true;
        $activeUserProgram->status = 'Aktif';
    }

    return view('participant.program.index', compact(
        'programs',
        'totalPrograms',
        'activePrograms',
        'completedPrograms',
        'upcomingPrograms',
        'activeUserProgram'
    ));
}

    /**
     * Menampilkan detail satu program.
     */
    public function show($id)
    {
        $user = auth()->user();
        $program = Program::withCount([
            'kelas as kelas_count',
            'participants as participants_count',
            'assignments as assignments_count'
        ])->with(['participants' => function($query) use ($user) {
            $query->where('users.id', $user->id);
        }])->findOrFail($id);

        // Hitung materials_count (modul + video)
        $program->materials_count = $program->kelas->sum(function($kelas) {
            return $kelas->modules->count() + $kelas->videoEmbeds->count();
        });

        $program->is_joined = $program->participants->contains($user->id);
        $program->status = $this->getProgramStatus($program);

        return view('participant.program.show', compact('program'));
    }

    /**
     * Helper method untuk menentukan status program
     */
    private function getProgramStatus($program)
    {
        $now = now();

        if (!$program->tanggal_mulai || !$program->tanggal_selesai) {
            return 'Self-Paced';
        } elseif ($now->between($program->tanggal_mulai, $program->tanggal_selesai)) {
            return 'Berlangsung';
        } elseif ($now->lt($program->tanggal_mulai)) {
            return 'Akan Datang';
        } else {
            return 'Selesai';
        }
    }
}
