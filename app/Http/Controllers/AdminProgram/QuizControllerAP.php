<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizControllerAP extends Controller
{
    // --- BAGIAN 1: MANAJEMEN INDUK KUIS ---

    /**
     * Form Tambah Kuis Baru.
     */
    public function create($kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.quizzes.create', compact('kelas'));
    }

    /**
     * Simpan Kuis Baru.
     */
    public function store(Request $request, $kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $quiz = Quiz::create([
                'kelas_id' => $kelasId,
                'title' => $request->title,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes,
                'max_attempts' => $request->max_attempts,
                'is_published' => $request->has('is_published'),
            ]);

            DB::commit();

            return redirect()->route('adminprogram.quizzes.edit', $quiz->id)
                             ->with('success', 'Kuis berhasil dibuat. Silakan tambah soal.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat kuis: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Halaman Edit Kuis (Dashboard Soal).
     */
    public function edit($id)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['kelas.program', 'questions.answers'])->findOrFail($id);

        if (!$user->administeredPrograms->contains($quiz->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.quizzes.edit', compact('quiz'));
    }

    /**
     * Update Info Kuis.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $quiz = Quiz::with('kelas.program')->findOrFail($id);

        if (!$user->administeredPrograms->contains($quiz->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:0',
        ]);

        try {
            $quiz->update([
                'title' => $request->title,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes,
                'max_attempts' => $request->max_attempts,
                'is_published' => $request->has('is_published'),
            ]);

            return back()->with('success', 'Pengaturan kuis berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update kuis: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Kuis.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $quiz = Quiz::with('kelas.program')->findOrFail($id);

        if (!$user->administeredPrograms->contains($quiz->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $kelasId = $quiz->kelas_id;

        DB::beginTransaction();
        try {
            foreach ($quiz->questions as $question) {
                if ($question->image_path) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $question->answers()->delete();
            }
            $quiz->questions()->delete();
            $quiz->delete();

            DB::commit();

            return redirect()->route('adminprogram.kelas.edit', $kelasId)
                             ->with('success', 'Kuis berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus kuis: ' . $e->getMessage());
        }
    }

    // --- BAGIAN 2: MANAJEMEN SOAL (QUESTIONS) ---

    /**
     * Simpan Soal Baru
     */
    public function storeQuestion(Request $request, $quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with('kelas.program')->findOrFail($quizId);

        if (!$user->administeredPrograms->contains($quiz->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Soal
            $questionData = [
                'quiz_id' => $quizId,
                'question_text' => $request->question_text,
            ];

            if ($request->hasFile('image')) {
                $questionData['image_path'] = $request->file('image')->store('quiz-images', 'public');
            }

            $question = Question::create($questionData);

            // 2. Simpan Jawaban (A, B, C, D, E)
            // PERBAIKAN: Gunakan option_text saja, tanpa content
            $labels = ['A', 'B', 'C', 'D', 'E'];
            foreach ($request->options as $index => $optionText) {
                Answer::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText, // Simpan teks jawaban di kolom option_text
                    'is_correct' => ($index == $request->correct_option),
                ]);
            }

            DB::commit();

            return redirect()->route('adminprogram.quizzes.edit', $quizId)
                             ->with('success', 'Soal berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal menyimpan soal: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Gagal menyimpan soal: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Form Edit Soal (Modal)
     */
    public function editQuestion($questionId)
    {
        $user = Auth::user();
        $question = Question::with(['quiz.kelas.program', 'answers'])->findOrFail($questionId);

        if (!$user->administeredPrograms->contains($question->quiz->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.quizzes.partials.question-form-edit', compact('question'));
    }

    /**
     * Update Soal
     */
    public function updateQuestion(Request $request, $questionId)
    {
        $user = Auth::user();
        $question = Question::with(['quiz.kelas.program', 'answers'])->findOrFail($questionId);

        if (!$user->administeredPrograms->contains($question->quiz->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'options' => 'required|array',
            'correct_option' => 'required', // ID dari jawaban yang benar
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Soal
            $data = ['question_text' => $request->question_text];

            // Handle image update/removal
            if ($request->has('remove_image') && $question->image_path) {
                Storage::disk('public')->delete($question->image_path);
                $data['image_path'] = null;
            }

            if ($request->hasFile('image')) {
                if ($question->image_path) {
                    Storage::disk('public')->delete($question->image_path);
                }
                $data['image_path'] = $request->file('image')->store('quiz-images', 'public');
            }

            $question->update($data);

            // 2. Update Jawaban
            // PERBAIKAN: Update option_text saja
            foreach ($request->options as $answerId => $answerText) {
                $isCorrect = ($answerId == $request->correct_option);

                Answer::where('id', $answerId)
                      ->where('question_id', $question->id)
                      ->update([
                          'option_text' => $answerText, // Update kolom option_text
                          'is_correct' => $isCorrect
                      ]);
            }

            DB::commit();

            return redirect()->route('adminprogram.quizzes.edit', $question->quiz_id)
                             ->with('success', 'Soal berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal update soal: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Gagal update soal: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Hapus Soal
     */
    public function destroyQuestion($questionId)
    {
        $user = Auth::user();
        $question = Question::with('quiz.kelas.program')->findOrFail($questionId);

        if (!$user->administeredPrograms->contains($question->quiz->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        DB::beginTransaction();
        try {
            $quizId = $question->quiz_id;

            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }

            $question->answers()->delete();
            $question->delete();

            DB::commit();

            return back()->with('success', 'Soal berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus soal: ' . $e->getMessage());
        }
    }
    
}
