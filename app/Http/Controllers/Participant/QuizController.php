<?php
namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Carbon\Carbon;

class QuizController extends Controller
{
    // 1. Halaman Intro (Cek Batas Pengerjaan)
    public function show($id)
    {
        $quiz = Quiz::with('kelas')->findOrFail($id);
        $user = Auth::user();

        // Cek History
        $attempts = QuizAttempt::where('quiz_id', $id)
                               ->where('user_id', $user->id)
                               ->orderBy('created_at', 'desc')
                               ->get();

        // Cek apakah ada sesi ongoing (belum selesai)
        $ongoing = $attempts->where('finished_at', null)->first();

        return view('participant.quiz.show', compact('quiz', 'attempts', 'ongoing'));
    }

    // 2. Mulai Ujian (Logika Batas Attempt)
    public function start($id)
    {
        $quiz = Quiz::findOrFail($id);
        $user = Auth::user();

        // Cek Sesi Ongoing (Lanjutkan yg belum selesai)
        $ongoing = QuizAttempt::where('quiz_id', $id)
                              ->where('user_id', $user->id)
                              ->whereNull('finished_at')
                              ->first();

        if ($ongoing) {
            return redirect()->route('participant.quiz.take', $ongoing->id);
        }

        // CEK BATAS MENGERJAKAN (Max Attempts)
        $count = QuizAttempt::where('quiz_id', $id)
                            ->where('user_id', $user->id)
                            ->whereNotNull('finished_at') // Hanya hitung yang sudah selesai
                            ->count();

        if ($quiz->max_attempts > 0 && $count >= $quiz->max_attempts) {
            return back()->with('error', 'Kesempatan mengerjakan ujian ini sudah habis.');
        }

        // Buat Sesi Baru
        $attempt = QuizAttempt::create([
            'quiz_id' => $id,
            'user_id' => $user->id,
            'started_at' => now(),
        ]);

        return redirect()->route('participant.quiz.take', $attempt->id);
    }

    // 3. Halaman Soal
public function take($attemptId)
{
    $attempt = QuizAttempt::with(['quiz.questions.answers'])
        ->where('id', $attemptId)
        ->where('user_id', Auth::id())
        ->whereNull('finished_at')
        ->firstOrFail();

    $quiz = $attempt->quiz;

    // Tentukan start time attempt
    if (!$attempt->started_at) {
        $attempt->update(['started_at' => now()]);
    }

    // Hitung remaining time
    $durationSeconds = $quiz->duration_minutes * 60;
    $elapsed = now()->diffInSeconds($attempt->started_at);
    $remainingSeconds = max($durationSeconds - $elapsed, 0);

    // SOAL ACAK
    $questions = $quiz->questions->shuffle();

    return view('participant.quiz.take', [
        'quiz' => $quiz,
        'attempt' => $attempt,
        'questions' => $questions,
        'remainingSeconds' => $remainingSeconds,
    ]);
}


public function submit(Request $request, $attemptId)
{
    $attempt = QuizAttempt::where('id', $attemptId)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    // Cegah submit dua kali
    if ($attempt->finished_at) {
        return redirect()->route('participant.quiz.result', $attemptId);
    }

    $quiz = $attempt->quiz;
    $totalQuestions = $quiz->questions->count();
    $submitted = $request->input('answers', []);

    $correct = 0;

    DB::beginTransaction();

    try {
        foreach ($quiz->questions as $question) {

            $userAnswer = $submitted[$question->id] ?? null;
            $correctAnswer = $question->correctAnswer;

            $isCorrect = ($userAnswer && $correctAnswer && $userAnswer == $correctAnswer->id);

            if ($isCorrect) {
                $correct++;
            }

            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'answer_id' => $userAnswer,     // null = tidak menjawab
                'is_correct' => $isCorrect,
            ]);
        }

        // SCORING
        $score = $totalQuestions ? round(($correct / $totalQuestions) * 100, 2) : 0;

        $attempt->update([
            'finished_at' => now(),
            'score' => $score,
            'correct_count' => $correct,
            'wrong_count' => $totalQuestions - $correct,
                'admin_feedback' => $request->feedback,
    'graded_at' => now(),
        ]);

        DB::commit();
        return redirect()->route('participant.quiz.result', $attemptId);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}

public function result($attemptId)
{
    $attempt = QuizAttempt::with(['quiz', 'quizAnswers.question', 'quizAnswers.answer'])
                          ->where('id', $attemptId)
                          ->where('user_id', Auth::id())
                          ->whereNotNull('finished_at')
                          ->firstOrFail();

    return view('participant.quiz.result', compact('attempt'));
}
}
