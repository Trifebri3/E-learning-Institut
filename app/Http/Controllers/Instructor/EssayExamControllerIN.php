<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EssayExam;
use App\Models\EssayQuestion;
use App\Models\EssaySubmission;
use App\Models\EssayAnswer;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;

class EssayExamControllerIN extends Controller
{
    /* =============================
     * 1. MANAJEMEN UJIAN - INDEX & CRUD
     * ============================= */

    /**
     * Menampilkan daftar semua ujian essay
     */
    public function index()
    {
        $exams = EssayExam::with('kelas')->orderBy('created_at', 'DESC')->get();
        return view('instructor.essay.index', compact('exams'));
    }

    /**
     * Form membuat ujian baru
     */
    public function create()
    {
        $kelas = Kelas::orderBy('id')->get();
        return view('instructor.essay.create', compact('kelas'));
    }

    /**
     * Menyimpan ujian baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'duration_minutes' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        EssayExam::create($validated);

        return redirect()->route('instructor.essay.index')
            ->with('success', 'Ujian berhasil dibuat');
    }

    /**
     * Form edit ujian
     */
    public function edit($id)
    {
        $exam = EssayExam::findOrFail($id);
        $kelas = Kelas::orderBy('id')->get();

        return view('instructor.essay.edit', compact('exam', 'kelas'));
    }

    /**
     * Update ujian
     */
    public function update(Request $request, $id)
    {
        $exam = EssayExam::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'duration_minutes' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        $exam->update($validated);

        return redirect()->route('instructor.essay.index')
            ->with('success', 'Ujian berhasil diperbarui');
    }

    /**
     * Hapus ujian
     */
    public function destroy($id)
    {
        $exam = EssayExam::findOrFail($id);
        $exam->delete();

        return back()->with('success', 'Ujian berhasil dihapus');
    }

    /* =============================
     * 2. MANAJEMEN SOAL
     * ============================= */

    /**
     * Menampilkan soal-soal untuk ujian tertentu
     */
    public function questions($examId)
    {
        $exam = EssayExam::with('questions')->findOrFail($examId);
        return view('instructor.essay.questions.index', compact('exam'));
    }

    /**
     * Menyimpan soal baru
     */
    public function storeQuestion(Request $request, $examId)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'max_score' => 'nullable|integer|min:1'
        ]);

        EssayQuestion::create([
            'essay_exam_id' => $examId,
            'question_text' => $validated['question_text'],
            'max_score' => $validated['max_score'] ?? 100
        ]);

        return back()->with('success', 'Soal berhasil ditambah');
    }

    /**
     * Update soal
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = EssayQuestion::findOrFail($id);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'max_score' => 'nullable|integer|min:1'
        ]);

        $question->update($validated);

        return back()->with('success', 'Soal berhasil diperbarui');
    }

    /**
     * Hapus soal
     */
    public function deleteQuestion($id)
    {
        $question = EssayQuestion::findOrFail($id);
        $question->delete();

        return back()->with('success', 'Soal berhasil dihapus');
    }

    /* =============================
     * 3. MANAJEMEN SUBMISSION
     * ============================= */

    /**
     * Menampilkan semua submission untuk ujian tertentu
     */
    public function submissions($examId)
    {
        $exam = EssayExam::findOrFail($examId);
        $submissions = EssaySubmission::with(['user.nomorInduk'])
            ->where('essay_exam_id', $examId)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('instructor.essay.submissions.index', compact('exam', 'submissions'));
    }

    /**
     * Form penilaian submission
     */
    public function gradeSubmission($submissionId)
    {
        $submission = EssaySubmission::with(['answers.question', 'user'])->findOrFail($submissionId);
        return view('instructor.essay.submissions.grade', compact('submission'));
    }

    /**
     * Menyimpan nilai untuk setiap jawaban
     */
    public function saveGrade(Request $request, $answerId)
    {
        $answer = EssayAnswer::findOrFail($answerId);

        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $answer->update([
            'score' => $validated['score'],
            'notes' => $validated['feedback']
        ]);

        return back()->with('success', 'Nilai & feedback berhasil disimpan');
    }

    /**
     * Menyelesaikan proses penilaian dan menghitung nilai akhir
     */
    public function finishGrading($submissionId)
    {
        $submission = EssaySubmission::with('answers')->findOrFail($submissionId);

        // Cek apakah semua soal sudah dinilai
        $ungradedAnswers = $submission->answers->whereNull('score');
        if ($ungradedAnswers->count() > 0) {
            return back()->with('error', 'Masih ada soal yang belum dinilai.');
        }

        // Hitung nilai akhir
        $finalScore = $this->calculateFinalScore($submissionId);

        // Update submission
        $submission->update([
            'final_score' => $finalScore,
            'status' => 'graded',
 
        ]);

        return redirect()->route('instructor.essay.submissions', $submission->essay_exam_id)
            ->with('success', 'Penilaian selesai! Nilai akhir: ' . number_format($finalScore, 2));
    }

    /**
     * Update nilai final dan feedback secara manual
     */
    public function updateFinalScore(Request $request, $id)
    {
        $submission = EssaySubmission::findOrFail($id);

        $validated = $request->validate([
            'final_score' => 'required|numeric|min:0|max:100',
            'admin_feedback' => 'nullable|string|max:5000',
        ]);

        $submission->update([
            'final_score' => $validated['final_score'],
            'admin_feedback' => $validated['admin_feedback'],
            'status' => 'graded'
        ]);

        return back()->with('success', 'Nilai & feedback berhasil disimpan!');
    }

    /* =============================
     * 4. EKSPOR & LAPORAN
     * ============================= */

    /**
     * Export single submission ke PDF
     */
    public function exportPDF($submissionId)
    {
        $submission = EssaySubmission::with(['user', 'answers.question', 'exam'])->findOrFail($submissionId);

        // Pastikan nilai sudah dihitung
        if (is_null($submission->final_score)) {
            $this->calculateFinalScore($submissionId);
            $submission->refresh();
        }

        $pdf = PDF::loadView('instructor.essay.submissions.pdf-single', compact('submission'));

        $filename = "Hasil-Essay-{$submission->user->name}.pdf";
        return $pdf->download($filename);
    }

    /**
     * Preview laporan sebelum export
     */
    public function previewPDF($examId)
    {
        $exam = EssayExam::findOrFail($examId);
        $submissions = EssaySubmission::with(['user.nomorInduk', 'answers'])
            ->where('essay_exam_id', $examId)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('instructor.essay.submissions.preview', compact('exam', 'submissions'));
    }

    /**
     * Export semua submission untuk ujian tertentu ke PDF
     */
    public function exportAllPDF($examId)
    {
        $exam = EssayExam::findOrFail($examId);
        $submissions = EssaySubmission::with(['user.nomorInduk', 'answers'])
            ->where('essay_exam_id', $examId)
            ->orderBy('created_at', 'DESC')
            ->get();

        $pdf = PDF::loadView('instructor.essay.submissions.pdf-all', compact('exam', 'submissions'));

        $filename = "Laporan-Submissions-{$exam->title}.pdf";
        return $pdf->download($filename);
    }

    /* =============================
     * 5. METHOD BANTU (PRIVATE)
     * ============================= */

    /**
     * Menghitung nilai final untuk submission
     */
    private function calculateFinalScore($submissionId)
    {
        $submission = EssaySubmission::with('answers')->findOrFail($submissionId);
        $answers = $submission->answers;

        if ($answers->count() == 0) {
            return 0;
        }

        // Hitung total score (ubah null jadi 0)
        $totalScore = $answers->sum(function ($answer) {
            return $answer->score ?? 0;
        });

        // Hitung rata-rata
        $finalScore = $totalScore / $answers->count();

        return round($finalScore, 2);
    }

    /**
     * Toggle status publish ujian
     */
    public function togglePublish($id)
    {
        $exam = EssayExam::findOrFail($id);
        $exam->update([
            'is_published' => !$exam->is_published
        ]);

        $status = $exam->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Ujian berhasil $status!");
    }
}
