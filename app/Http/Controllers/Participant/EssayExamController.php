<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\EssayExam;
use App\Models\EssaySubmission;
use App\Models\EssayAnswer;

class EssayExamController extends Controller
{
    /**
     * 1. Halaman Intro / Informasi Ujian
     */
    public function show($id)
    {
        // Ambil exam dengan kelas & program
        $exam = EssayExam::with('kelas.program')->findOrFail($id);

        // Security check: pastikan user punya akses ke program kelas
        $userProgramIds = Auth::user()->programs()->pluck('id'); // pastikan relasi programs() ada di User
        if (!$userProgramIds->contains($exam->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        // Cek apakah user sudah pernah submit / ongoing
        $submission = $exam->submissions()
                           ->where('user_id', Auth::id())
                           ->latest()
                           ->first();

        // Jika ongoing, user akan melanjutkan
        if ($submission && $submission->status === 'ongoing') {
            $buttonText = 'LANJUTKAN MENGERJAKAN';
        } elseif ($submission && in_array($submission->status, ['submitted', 'graded'])) {
            $buttonText = 'LIHAT STATUS / NILAI';
        } else {
            $buttonText = 'MULAI UJIAN ESAI';
        }

        return view('participant.essay.show', compact('exam', 'submission', 'buttonText'));
    }


    /**
     * 2. Mulai Ujian
     */
    public function start($examId)
    {
        $exam = EssayExam::findOrFail($examId);
        $user = Auth::user();

        // Cek jika masih ada session ongoing
        $ongoing = EssaySubmission::where('essay_exam_id', $examId)
            ->where('user_id', $user->id)
            ->where('status', 'ongoing')
            ->first();

        if ($ongoing) {
            return redirect()->route('participant.essay.take', $ongoing->id);
        }

        // Cek jika sudah pernah submit
        $submitted = EssaySubmission::where('essay_exam_id', $examId)
            ->where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->first();

        if ($submitted) {
            return redirect()->route('participant.essay.result', $submitted->id);
        }

        // Buat submission baru
        $submission = EssaySubmission::create([
            'essay_exam_id' => $examId,
            'user_id' => $user->id,
            'started_at' => now(),
            'status' => 'ongoing'
        ]);

        return redirect()->route('participant.essay.take', $submission->id);
    }


    /**
     * 3. Halaman Pengerjaan Ujian
     */
   public function take($submissionId)
{
    $submission = EssaySubmission::with('exam.questions') // eager load exam & questions
        ->where('id', $submissionId)
        ->where('user_id', auth()->id())
        ->where('status', 'ongoing')
        ->firstOrFail();

    $exam = $submission->exam;

    // Jika exam tidak ada, hentikan proses
    if (!$exam) {
        abort(404, 'Data ujian tidak ditemukan untuk submission ini.');
    }

    // Hitung waktu berakhir
    $startedAt = $submission->started_at instanceof Carbon
        ? $submission->started_at
        : Carbon::parse($submission->started_at);

    $endTime = $startedAt->copy()->addMinutes($exam->duration_minutes);

    // Jika waktu habis → auto submit kosong
    if (now()->greaterThan($endTime)) {
        return $this->autoSubmit($submission);
    }

    // Ambil jawaban yang sudah ada (draft)
    $savedAnswers = $submission->answers
        ->pluck('answer_text', 'essay_question_id')
        ->toArray();

    return view('participant.essay.take', compact('submission', 'exam', 'savedAnswers'));
}


    /**
     * 4. Proses Submit Manual dari User
     */
    public function submit(Request $request, $submissionId)
    {
        $submission = EssaySubmission::where('id', $submissionId)
            ->where('user_id', Auth::id())
            ->where('status', 'ongoing')
            ->firstOrFail();

        $answers = $request->input('answers', []);

        return $this->saveSubmission($submission, $answers);
    }


    /**
     * 4B. Auto Submit jika Timer Habis
     */
    private function autoSubmit($submission)
    {
        return $this->saveSubmission($submission, []);
    }


    /**
     * Helper penuh untuk menyimpan jawaban
     */
    private function saveSubmission($submission, $answers)
    {
        DB::beginTransaction();
        try {
            foreach ($answers as $questionId => $answerText) {
                EssayAnswer::updateOrCreate(
                    [
                        'essay_submission_id' => $submission->id,
                        'essay_question_id' => $questionId
                    ],
                    [
                        'answer_text' => $answerText
                    ]
                );
            }

            // Update status submission
            $submission->update([
                'submitted_at' => now(),
                'status' => 'submitted'
            ]);

            DB::commit();

            return redirect()
                ->route('participant.essay.result', $submission->id)
                ->with('success', 'Jawaban berhasil dikirim.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * 5. Halaman Hasil / Status Penilaian
     */
    public function result($submissionId)
    {
$submission = EssaySubmission::with([
    'exam',
    'answers.question'
])->where('id', $submissionId)
  ->where('user_id', auth()->id())
  ->firstOrFail();


        return view('participant.essay.result', compact('submission'));
    }



    /**
 * 6. Halaman Preview Jawaban (Sebelum dinilai)
 */
public function preview($examId)
{
    $exam = EssayExam::with(['questions', 'kelas.program'])->findOrFail($examId);
    $user = Auth::user();

    // Security check: pastikan user punya akses ke program kelas - FIX AMBIGUOUS COLUMN
    $userProgramIds = $user->programs()->pluck('programs.id'); // Specify table name
    if (!$userProgramIds->contains($exam->kelas->program_id)) {
        abort(403, 'Akses Ditolak.');
    }

    // Ambil submission terakhir user untuk exam ini
    $submission = EssaySubmission::with('answers')
        ->where('essay_exam_id', $examId)
        ->where('user_id', $user->id)
        ->latest()
        ->first();

    if (!$submission) {
        return redirect()->route('participant.essay.show', $examId)
            ->with('error', 'Anda belum mengerjakan ujian ini.');
    }

    return view('participant.essay.result', compact('exam', 'submission'));
}
    /**
     * 7. Auto-save Jawaban (AJAX)
     */
    public function autoSave(Request $request, $submissionId)
    {
        $submission = EssaySubmission::where('id', $submissionId)
            ->where('user_id', Auth::id())
            ->where('status', 'ongoing')
            ->firstOrFail();

        $questionId = $request->input('question_id');
        $answerText = $request->input('answer_text');

        try {
            EssayAnswer::updateOrCreate(
                [
                    'essay_submission_id' => $submission->id,
                    'essay_question_id' => $questionId
                ],
                [
                    'answer_text' => $answerText
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Jawaban tersimpan otomatis',
                'saved_at' => now()->format('H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jawaban'
            ], 500);
        }
    }

    /**
     * 8. Cek Waktu Tersisa (AJAX)
     */
    public function checkTime($submissionId)
    {
        $submission = EssaySubmission::with('exam')
            ->where('id', $submissionId)
            ->where('user_id', Auth::id())
            ->where('status', 'ongoing')
            ->firstOrFail();

        $startedAt = Carbon::parse($submission->started_at);
        $endTime = $startedAt->copy()->addMinutes($submission->exam->duration_minutes);
        $remaining = now()->diffInSeconds($endTime, false);

        // Auto submit jika waktu habis
        if ($remaining <= 0) {
            $this->autoSubmit($submission);
            return response()->json([
                'time_up' => true,
                'redirect_url' => route('participant.essay.result', $submission->id)
            ]);
        }

        return response()->json([
            'time_up' => false,
            'remaining_seconds' => $remaining,
            'remaining_time' => $this->formatRemainingTime($remaining)
        ]);
    }

    /**
     * 9. Format waktu tersisa
     */
    private function formatRemainingTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * 10. Reset Ujian (Hapus submission dan mulai ulang)
     */
    public function reset($examId)
    {
        $exam = EssayExam::findOrFail($examId);
        $user = Auth::user();

        // Cari submission yang ongoing/submitted
        $submission = EssaySubmission::where('essay_exam_id', $examId)
            ->where('user_id', $user->id)
            ->first();

        if ($submission) {
            // Hapus jawaban terkait
            EssayAnswer::where('essay_submission_id', $submission->id)->delete();

            // Hapus submission
            $submission->delete();
        }

        return redirect()->route('participant.essay.start', $examId)
            ->with('success', 'Ujian telah direset. Silakan mulai kembali.');
    }

    /**
     * 11. List Ujian Essay oleh Kelas
     */
    public function byKelas($kelasId)
    {
        $kelas = \App\Models\Kelas::with(['essayExams' => function($query) {
            $query->where('is_published', true)
                  ->orderBy('created_at', 'desc');
        }])->findOrFail($kelasId);

        $user = Auth::user();

        // Security check
        $userProgramIds = $user->programs()->pluck('id');
        if (!$userProgramIds->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        // Ambil status submission untuk setiap exam
        $examsWithStatus = [];
        foreach ($kelas->essayExams as $exam) {
            $submission = EssaySubmission::where('essay_exam_id', $exam->id)
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            $examsWithStatus[] = [
                'exam' => $exam,
                'submission' => $submission,
                'has_attempt' => $submission !== null,
                'is_graded' => $submission && $submission->status === 'graded',
                'score' => $submission ? $submission->final_score : null
            ];
        }

        return view('participant.essay.by-kelas', compact('kelas', 'examsWithStatus'));
    }

    /**
     * 12. Download Soal (PDF)
     */
    public function downloadPdf($examId)
    {
        $exam = EssayExam::with('questions')->findOrFail($examId);
        $user = Auth::user();

        // Security check
        $userProgramIds = $user->programs()->pluck('id');
        if (!$userProgramIds->contains($exam->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        // Logic untuk generate PDF
        // Anda bisa menggunakan library seperti DomPDF atau Laravel PDF
        // Contoh:
        // $pdf = PDF::loadView('participant.essay.pdf', compact('exam'));
        // return $pdf->download("soal-essay-{$exam->title}.pdf");

        return redirect()->back()->with('info', 'Fitur download PDF akan segera tersedia.');
    }

    /**
     * 13. Statistik Ujian
     */
    public function statistics($examId)
    {
        $exam = EssayExam::with(['kelas.program'])->findOrFail($examId);
        $user = Auth::user();

        // Security check
        $userProgramIds = $user->programs()->pluck('id');
        if (!$userProgramIds->contains($exam->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $submission = EssaySubmission::with('answers.question')
            ->where('essay_exam_id', $examId)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$submission) {
            return redirect()->route('participant.essay.show', $examId)
                ->with('error', 'Anda belum mengerjakan ujian ini.');
        }

        $statistics = [
            'total_questions' => $exam->questions->count(),
            'answered_questions' => $submission->answers->count(),
            'graded_questions' => $submission->answers->whereNotNull('score')->count(),
            'total_score' => $submission->answers->sum('score'),
            'max_possible_score' => $exam->questions->sum('max_score'),
            'completion_percentage' => $exam->questions->count() > 0
                ? round(($submission->answers->count() / $exam->questions->count()) * 100, 2)
                : 0
        ];

        return view('participant.essay.statistics', compact('exam', 'submission', 'statistics'));
    }
}
