<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Submission;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    /**
     * Menampilkan hanya tugas prioritas (belum dikumpulkan & belum lewat deadline)
     */
    public function index()
    {
        $user = Auth::user();
        $programIds = $user->programs()->pluck('programs.id');

        // Ambil hanya tugas yang belum dikumpulkan dan belum lewat deadline
        $urgentAssignments = Assignment::whereHas('kelas', function ($query) use ($programIds) {
                            $query->whereIn('program_id', $programIds);
                        })
                        ->where('is_published', true)
                        ->where('due_date', '>', now()) // Deadline belum lewat
                        ->whereDoesntHave('submissions', function ($query) use ($user) {
                            $query->where('user_id', $user->id); // Belum ada submission
                        })
                        ->with(['kelas.program'])
                        ->orderBy('due_date', 'asc') // Urutkan berdasarkan deadline terdekat
                        ->get();

        return view('participant.assignments.index', compact('urgentAssignments', 'user'));
    }

    /**
     * Menampilkan semua tugas (halaman terpisah)
     */
    public function allAssignments()
    {
        $user = Auth::user();
        $programIds = $user->programs()->pluck('programs.id');

        // Ambil semua tugas
        $assignments = Assignment::whereHas('kelas', function ($query) use ($programIds) {
                            $query->whereIn('program_id', $programIds);
                        })
                        ->where('is_published', true)
                        ->with(['kelas.program'])
                        ->orderBy('due_date', 'asc')
                        ->get();

        // Kelompokkan berdasarkan program
        $groupedAssignments = $assignments->groupBy(fn($a) => $a->kelas->program->title);

        return view('participant.assignments.all', compact('groupedAssignments', 'user'));
    }

    /**
     * Menampilkan detail satu tugas.
     */
    public function show($id)
    {
        $user = Auth::user();
        $assignment = Assignment::with(['kelas.program'])
                            ->where('is_published', true)
                            ->findOrFail($id);

        $userProgramIds = $user->programs()->pluck('programs.id');
        if (!$userProgramIds->contains($assignment->kelas->program_id)) {
            abort(403, 'Akses ditolak. Tugas bukan milik program Anda.');
        }

        $submission = $assignment->userSubmission($user->id);

        return view('participant.assignments.show', compact('assignment', 'submission'))
               ->with('now', Carbon::now());
    }

    /**
     * Memproses pengumpulan tugas dari peserta.
     */
    public function submit(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $user = Auth::user();

        // Cek jika sudah pernah submit
        if ($assignment->userSubmission($user->id)) {
            return back()->with('error', 'Anda hanya diizinkan satu kali pengumpulan tugas ini.');
        }

        $request->validate([
            'submission_link' => 'required|url',
            'notes' => 'nullable|string|max:500',
        ]);

        $submittedAt = Carbon::now();
        $isLate = $submittedAt->greaterThan($assignment->due_date);

        Submission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'submission_link' => $request->submission_link,
            'notes' => $request->notes,
            'submitted_at' => $submittedAt,
            'is_late' => $isLate,
                'score' => $request->score,
    'admin_feedback' => $request->feedback,
    'graded_at' => Carbon::now(),
        ]);

        return redirect()->route('participant.assignments.show', $assignment->id)
                         ->with('success', 'Tugas berhasil dikumpulkan! ' . ($isLate ? '(Terlambat)' : ''));
    }

    /**
     * API untuk mendapatkan count tugas prioritas (untuk badge/notifikasi)
     */
    public function getUrgentCount()
    {
        $user = Auth::user();
        $programIds = $user->programs()->pluck('programs.id');

        $urgentCount = Assignment::whereHas('kelas', function ($query) use ($programIds) {
                            $query->whereIn('program_id', $programIds);
                        })
                        ->where('is_published', true)
                        ->where('due_date', '>', now())
                        ->where('due_date', '<=', now()->addDays(3)) // Deadline dalam 3 hari
                        ->whereDoesntHave('submissions', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->count();

        return response()->json(['urgent_count' => $urgentCount]);
    }
}
