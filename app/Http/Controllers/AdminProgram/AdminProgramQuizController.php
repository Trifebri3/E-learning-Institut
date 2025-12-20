<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\Kelas;
use App\Models\Program;
use Carbon\Carbon;
use DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AdminProgramQuizController extends Controller
{
    // Daftar quiz
    public function index($programId = null)
    {
        $query = Quiz::with(['kelas.program']);

        if ($programId) {
            $query->whereHas('kelas', fn($q) => $q->where('program_id', $programId));
        }

        $quizzes = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('adminprogram.quiz.index', compact('quizzes', 'programId'));
    }

    // Form create
    public function create()
    {
        $programs = Program::all();
        $kelasList = Kelas::with('program')->get();
        return view('adminprogram.quiz.create', compact('programs', 'kelasList'));
    }

    // Store quiz baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'is_published' => 'required|boolean',
        ]);

        Quiz::create($request->all());
        return redirect()->route('adminprogram.quiz.index')
                         ->with('success', 'Quiz berhasil dibuat!');
    }

    // Edit quiz
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        $programs = Program::all();
        $kelasList = Kelas::with('program')->get();

        return view('adminprogram.quiz.edit', compact('quiz', 'programs', 'kelasList'));
    }

    // Update quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'is_published' => 'required|boolean',
        ]);

        $quiz->update($request->all());
        return redirect()->route('adminprogram.quiz.index')
                         ->with('success', 'Quiz berhasil diperbarui!');
    }

    // Hapus quiz
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('adminprogram.quiz.index')
                         ->with('success', 'Quiz berhasil dihapus!');
    }

    // Lihat semua submission
    public function submissions($quizId)
    {
        $quiz = Quiz::with(['quizAttempts.user'])->findOrFail($quizId);
        return view('adminprogram.quiz.submissions', compact('quiz'));
    }

    // Update nilai submission
    public function updateSubmissionScore(Request $request, $attemptId)
    {
        $attempt = QuizAttempt::findOrFail($attemptId);

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:500',
        ]);

        $attempt->update([
            'score' => $request->score,
            'admin_feedback' => $request->feedback,
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Nilai & feedback berhasil diperbarui!');
    }

    // Download semua submission
    public function downloadAllSubmissions($quizId)
    {
        $quiz = Quiz::with(['quizAttempts.user'])->findOrFail($quizId);
        $pdf = PDF::loadView('adminprogram.quiz.pdf-all', compact('quiz'));
        return $pdf->download("submissions_quiz_{$quiz->id}.pdf");
    }
}
