<?php

namespace App\Http\Controllers\AdminProgram;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EssayExam;
use App\Models\EssayQuestion;
use App\Models\EssaySubmission;
use App\Models\EssayAnswer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
class EssayExamControllerAP extends Controller
{
    /* =============================
     * 1. LIST UJIAN
     * ============================= */
    public function index()
    {
        $exams = EssayExam::with('kelas')->orderBy('created_at', 'DESC')->get();
        return view('adminprogram.essay.index', compact('exams'));
    }

    /* =============================
     * 2. CREATE UJIAN
     * ============================= */
public function create()
{
return view('adminprogram.essay.create', [
    'kelas' => \App\Models\Kelas::orderBy('id')->get(),
]);

}


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'kelas_id' => 'required',
            'duration_minutes' => 'required|numeric'
        ]);

        EssayExam::create($request->all());

        return redirect()->route('adminprogram.essay.index')
            ->with('success', 'Ujian berhasil dibuat');
    }


    /* =============================
     * 3. EDIT / UPDATE UJIAN
     * ============================= */
public function edit($id)
{
    $exam = EssayExam::findOrFail($id);

    return view('adminprogram.essay.edit', [
        'exam'  => $exam,
        'kelas' => \App\Models\Kelas::orderBy('id')->get(),
    ]);
}


    public function update(Request $request, $id)
    {
        $exam = EssayExam::findOrFail($id);
        $exam->update($request->all());

        return redirect()->route('adminprogram.essay.index')
            ->with('success', 'Ujian berhasil diperbarui');
    }


    /* =============================
     * 4. DELETE UJIAN
     * ============================= */
    public function destroy($id)
    {
        EssayExam::findOrFail($id)->delete();

        return back()->with('success', 'Ujian berhasil dihapus');
    }


    /* =============================
     * 5. KELOLA SOAL
     * ============================= */

    public function questions($examId)
    {
        $exam = EssayExam::with('questions')->findOrFail($examId);
        return view('adminprogram.essay.questions.index', compact('exam'));
    }

    public function storeQuestion(Request $request, $examId)
    {
        $request->validate([
            'question_text' => 'required'
        ]);

        EssayQuestion::create([
            'essay_exam_id' => $examId,
            'question_text' => $request->question_text
        ]);

        return back()->with('success', 'Soal berhasil ditambah');
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = EssayQuestion::findOrFail($id);
        $question->update(['question_text' => $request->question_text]);

        return back()->with('success', 'Soal diperbarui');
    }

    public function deleteQuestion($id)
    {
        EssayQuestion::findOrFail($id)->delete();
        return back()->with('success', 'Soal dihapus');
    }


    /* =============================
     * 6. LIST SUBMISSION PESERTA
     * ============================= */
    public function submissions($examId)
    {
        $exam = EssayExam::findOrFail($examId);
$submissions = EssaySubmission::with(['user.nomorInduk'])
    ->where('essay_exam_id', $examId)
    ->get();


        return view('adminprogram.essay.submissions.index', compact('exam', 'submissions'));
    }


    /* =============================
     * 7. NILAI SETIAP SUBMISSION
     * ============================= */
    public function gradeSubmission($submissionId)
    {
        $submission = EssaySubmission::with(['answers.question'])->findOrFail($submissionId);

        return view('adminprogram.essay.submissions.grade', compact('submission'));
    }

    /* SIMPAN NILAI JAWABAN */
    public function saveGrade(Request $request, $answerId)
    {
        $answer = EssayAnswer::findOrFail($answerId);

$answer->update([
    'score' => $request->score,
    'notes' => $request->feedback
]);

        return back()->with('success', 'Nilai & Feedback disimpan');
    }

    /* DONE: FINALIZE SUBMISSION */
    // Finalization moved to the implementation below that also calculates and stores the final score.


private function calculateFinalScore($submissionId)
{
    $submission = EssaySubmission::with('answers')->findOrFail($submissionId);
    $answers = $submission->answers;

    if ($answers->count() == 0) {
        return 0;
    }

    // Ubah NULL score jadi 0
    $totalScore = $answers->map(function ($a) {
        return $a->score ?? 0;
    })->sum();

    // Rata-rata nilai
    $finalScore = $totalScore / $answers->count();

    // Simpan
    $submission->update([
        'final_score' => $finalScore
    ]);

    return $finalScore;
}


public function finishGrading($submissionId)
{
    $submission = EssaySubmission::findOrFail($submissionId);

    // Ambil semua answer yang sudah dinilai
    $answers = $submission->answers()->whereNotNull('score')->get();

    if ($answers->count() === 0) {
        return back()->with('error', 'Belum ada soal yang dinilai.');
    }

    // Hitung nilai akhir
    $totalScore = $answers->sum('score');
    $finalScore = $totalScore / $answers->count();

    // Simpan
    $submission->update([
        'final_score' => $finalScore,
        'status'      => 'graded'
    ]);

    return redirect()->route('adminprogram.essay.submissions', $submission->essay_exam_id)
        ->with('success', 'Penilaian selesai! Nilai akhir: ' . number_format($finalScore, 2));
}



public function exportPDF($submissionId)
{
    $submission = EssaySubmission::with(['user', 'answers.question', 'exam'])->findOrFail($submissionId);

    // Pastikan nilai sudah dihitung
    $this->calculateFinalScore($submissionId);

    $pdf = PDF::loadView('adminprogram.essay.submissions.pdf', [
        'submission' => $submission
    ]);

    return $pdf->download('Hasil-Essay-' . $submission->user->name . '.pdf');
}
public function updateFinalScore(Request $request, $id)
{
    $request->validate([
        'final_score' => 'required|numeric|min:0|max:100',
        'admin_feedback' => 'nullable|string|max:5000',
    ]);

    $submission = \App\Models\EssaySubmission::findOrFail($id);

    $submission->final_score = $request->final_score;
    $submission->admin_feedback = $request->admin_feedback;
    $submission->status = 'graded';
    $submission->save();

    return back()->with('success', 'Nilai & feedback berhasil disimpan!');
}

public function previewPDF($examId)
{
    $exam = EssayExam::findOrFail($examId);

    $submissions = EssaySubmission::with(['user.nomorInduk'])
        ->where('essay_exam_id', $examId)
        ->get();

    return view('adminprogram.essay.submissions.preview', compact('exam', 'submissions'));
}

public function exportAllPDF($examId)
{
    $exam = EssayExam::findOrFail($examId);

    $submissions = EssaySubmission::with(['user.nomorInduk'])
        ->where('essay_exam_id', $examId)
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'adminprogram.essay.submissions.pdf-all',
        compact('exam', 'submissions')
    );

    return $pdf->download("Laporan-Submissions-{$exam->title}.pdf");
}



}
