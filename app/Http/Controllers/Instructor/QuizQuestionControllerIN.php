<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizQuestionControllerIN extends Controller
{
      // Daftar soal untuk quiz tertentu
    public function index($quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        return view('instructor.quiz.questions.index', compact('quiz'));
    }

    // Form tambah soal
    public function create($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        return view('instructor.quiz.questions.create', compact('quiz'));
    }

    // Simpan soal baru
public function store(Request $request, $quizId)
{
    $request->validate([
        'question' => 'required|string',
        'type' => 'required|in:multiple_choice,essay',
    ]);

    // simpan soal
    $question = Question::create([
        'quiz_id' => $quizId,
        'question_text' => $request->question,
    ]);

    // jika pilihan ganda → buat 4 jawaban kosong
    if ($request->type === 'multiple_choice') {
        foreach (range(1, 4) as $i) {
Answer::create([
    'question_id' => $question->id,
    'option_text' => "Pilihan $i",
    'is_correct' => false,
]);

        }
    }

    return redirect()
        ->route('instructor.quiz.questions.edit', [$quizId, $question->id])
        ->with('success', 'Soal berhasil dibuat. Silakan isi pilihan ganda.');
}


    // Form edit soal
public function edit($quizId, $questionId)
{
    $quiz = Quiz::findOrFail($quizId);
    $question = Question::findOrFail($questionId);

    // cari soal sebelumnya
    $previous = Question::where('quiz_id', $quizId)
        ->where('id', '<', $questionId)
        ->orderBy('id', 'desc')
        ->first();

    // cari soal berikutnya
    $next = Question::where('quiz_id', $quizId)
        ->where('id', '>', $questionId)
        ->orderBy('id', 'asc')
        ->first();

    return view('instructor.quiz.questions.edit', compact('quiz', 'question', 'previous', 'next'));
}

    // Update soal
public function update(Request $request, $quizId, $questionId)
{
    $question = Question::findOrFail($questionId);

    // update teks soal
    $question->update([
        'question_text' => $request->question
    ]);

    // jika pilihan ganda → update jawaban
    if ($question->answers->count() > 0) {

        // pastikan correct_answer ada
        $correct = $request->correct_answer;

        foreach ($question->answers as $answer) {
            $answer->update([
                'option_text' => $request->answers[$answer->id] ?? '',
                'is_correct'  => ($correct == $answer->id),
            ]);
        }
    }

    return back()->with('success', 'Soal berhasil diperbarui!');
}


    // Hapus soal
    public function destroy($quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();

        return redirect()->route('instructor.quiz.questions.index', $quizId)
                         ->with('success', 'Soal berhasil dihapus.');
    }
}
