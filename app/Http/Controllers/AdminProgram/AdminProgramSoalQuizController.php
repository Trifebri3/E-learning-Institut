<?php
namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class AdminProgramSoalQuizController extends Controller
{
    /**
     * Tampilkan semua soal dari satu quiz
     */
    public function index($quizId)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($quizId);
        $questions = $quiz->questions;

        return view('adminprogram.quiz.soal.index', compact('quiz', 'questions'));
    }

    /**
     * Form buat soal baru
     */
    public function create($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        return view('adminprogram.quiz.soal.create', compact('quiz'));
    }

    /**
     * Simpan soal baru
     */
    public function store(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $request->validate([
            'question_text' => 'required|string',
            'answers' => 'required|array|min:2',
            'answers.*.text' => 'required|string',
            'correct_answer_index' => 'required|integer',
        ]);

        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
        ]);

foreach ($request->answers as $index => $ans) {
    $question->answers()->create([
        'option_text' => $ans['text'], // ganti text → option_text
        'is_correct' => ($index == $request->correct_answer_index),
    ]);
}


        return redirect()->route('adminprogram.quiz.soal.index', $quiz->id)
                         ->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Form edit soal
     */
    public function edit($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::with('answers')->findOrFail($questionId);

        return view('adminprogram.quiz.soal.edit', compact('quiz', 'question'));
    }

    /**
     * Update soal
     */
    public function update(Request $request, $quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);

        $request->validate([
            'question_text' => 'required|string',
            'answers' => 'required|array|min:2',
            'answers.*.text' => 'required|string',
            'correct_answer_index' => 'required|integer',
        ]);

        $question->update(['question_text' => $request->question_text]);

        // Update jawaban
foreach ($question->answers as $index => $ans) {
    if (isset($request->answers[$index])) {
        $ans->update([
            'option_text' => $request->answers[$index]['text'], // ganti text → option_text
            'is_correct' => ($index == $request->correct_answer_index),
        ]);
    }
}


        return redirect()->route('adminprogram.quiz.soal.index', $quizId)
                         ->with('success', 'Soal berhasil diperbarui!');
    }

    /**
     * Hapus soal
     */
    public function destroy($quizId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->answers()->delete();
        $question->delete();

        return redirect()->route('adminprogram.quiz.soal.index', $quizId)
                         ->with('success', 'Soal berhasil dihapus!');
    }
}
