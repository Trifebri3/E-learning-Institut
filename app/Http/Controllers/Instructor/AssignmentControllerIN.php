<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Program;
use App\Models\Kelas;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AssignmentControllerIN extends Controller
{
    /**
     * Daftar semua tugas dalam program tertentu
     */
    public function index(Request $request, $programId = null)
    {
        $query = Assignment::with(['kelas.program', 'submissions.user']);

        if ($programId) {
            $query->whereHas('kelas', fn($q) => $q->where('program_id', $programId));
        }

        $assignments = $query->orderBy('due_date', 'asc')->paginate(10);

        return view('instructor.assignments.index', compact('assignments', 'programId'));
    }

    /**
     * Form buat tugas baru
     */
    public function create()
    {
        $programs = Program::all();
        $kelasList = Kelas::with('program')->get();

        return view('instructor.assignments.create', compact('programs', 'kelasList'));
    }

    /**
     * Simpan tugas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'     => 'required|exists:kelas,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'due_date'     => 'required|date|after:today',
            'max_points'   => 'nullable|integer|min:1',
            'is_published' => 'required|boolean',
        ]);

        Assignment::create($request->only([
            'kelas_id',
            'title',
            'description',
            'due_date',
            'max_points',
            'is_published'
        ]));

        return redirect()->route('instructor.assignments.index')
                         ->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Form edit tugas
     */
    public function edit($id)
    {
        $assignment = Assignment::findOrFail($id);
        $programs = Program::all();
        $kelasList = Kelas::with('program')->get();

        return view('instructor.assignments.edit', compact('assignment', 'programs', 'kelasList'));
    }

    /**
     * Update tugas
     */
    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:today',
            'max_points' => 'nullable|integer|min:1',
            'is_published' => 'required|boolean',
        ]);

        $assignment->update($request->only([
            'kelas_id',
            'title',
            'description',
            'due_date',
            'max_points',
            'is_published'
        ]));

        return redirect()->route('instructor.assignments.index')
                         ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Hapus tugas
     */
    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('instructor.assignments.index')
                         ->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Lihat semua submission untuk satu tugas
     */
    public function submissions($id)
    {
        $assignment = Assignment::with(['submissions.user'])->findOrFail($id);
        return view('instructor.assignments.submissions', compact('assignment'));
    }

    /**
     * Update nilai dan feedback dari admin
     */
    public function updateSubmissionScore(Request $request, $submissionId)
    {
        $submission = Submission::findOrFail($submissionId);

        $request->validate([
            'score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string|max:500',
        ]);

        // Validasi max_points jika ada
        if ($submission->assignment->max_points) {
            $request->validate([
                'score' => 'max:' . $submission->assignment->max_points,
            ]);
        }

        $submission->update([
            'score' => $request->score,
            'admin_feedback' => $request->feedback,
            'graded_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Nilai & feedback berhasil diperbarui!');
    }

    /**
     * Download semua submission sebagai PDF
     */
    public function downloadAllSubmissions($assignmentId)
    {
        $assignment = Assignment::with(['submissions.user'])->findOrFail($assignmentId);
        $submissions = $assignment->submissions;

        $pdf = PDF::loadView('instructor.assignments.pdf-all', compact('assignment', 'submissions'));
        return $pdf->download("submissions_assignment_{$assignment->id}.pdf");
    }

    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->update([
            'is_published' => !$assignment->is_published
        ]);

        $status = $assignment->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Tugas berhasil $status!");
    }
}
