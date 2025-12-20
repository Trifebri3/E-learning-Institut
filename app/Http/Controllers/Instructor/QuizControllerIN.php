<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizControllerIN extends Controller
{
    /* =============================
     * 1. AUTHORIZATION CHECK - DIPERBAIKI
     * ============================= */

    /**
     * Check if user has access to the program - LEBIH FLEKSIBEL
     */
    private function checkAuthorization($programId)
    {
        $user = Auth::user();
        $accessibleProgramIds = array_unique(array_merge(
            $user->administeredPrograms()->pluck('programs.id')->toArray(),
            method_exists($user, 'instructedPrograms') ? $user->instructedPrograms()->pluck('programs.id')->toArray() : []
        ));

        if (!in_array($programId, $accessibleProgramIds)) {
            abort(403, 'Akses Ditolak.');
        }
    }
/**
 * Menampilkan daftar semua kuis dari kelas yang dikelola
 */
public function index(Request $request)
{
    $user = Auth::user();

    // Ambil ID program yang dikelola instructor
    $programIds = $user->administeredPrograms()->pluck('programs.id');

    $query = Quiz::whereHas('kelas', function($q) use ($programIds) {
            $q->whereIn('program_id', $programIds);
        })
        ->with(['kelas.program', 'questions'])
        ->withCount('questions')
        ->orderBy('created_at', 'desc');

    // Filter berdasarkan kelas
    if ($request->has('kelas_id') && $request->kelas_id != '') {
        $query->where('kelas_id', $request->kelas_id);
        $selectedKelasId = $request->kelas_id;
    } else {
        $selectedKelasId = null;
    }

    // Filter status publikasi
    if ($request->has('status') && $request->status != '') {
        if ($request->status == 'published') {
            $query->where('is_published', true);
        } elseif ($request->status == 'draft') {
            $query->where('is_published', false);
        }
    }

    $quizzes = $query->paginate(10);

    // Ambil kelas untuk filter dropdown
    $kelas = Kelas::whereIn('program_id', $programIds)
                 ->orderBy('title')
                 ->get();

    // Ambil kelas pertama untuk default create link
    $firstKelas = $kelas->first();

    return view('instructor.quizzes.index', compact('quizzes', 'kelas', 'selectedKelasId', 'firstKelas'));
}
    /* =============================
     * 2. MANAJEMEN INDUK KUIS
     * ============================= */

    /**
     * Form Tambah Kuis Baru.
     */
    public function create($kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        $this->checkAuthorization($kelas->program_id);

        return view('instructor.quizzes.create', compact('kelas'));
    }

    /**
     * Simpan Kuis Baru.
     */
    public function store(Request $request, $kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        $this->checkAuthorization($kelas->program_id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'is_published' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $quiz = Quiz::create([
                'kelas_id' => $kelasId,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'max_attempts' => $validated['max_attempts'],
                'is_published' => $validated['is_published'] ?? false,
            ]);

            DB::commit();

            // Redirect sesuai role
            if ($user->hasRole('instructor')) {
                return redirect()->route('instructor.quizzes.edit', $quiz->id)
                                 ->with('success', 'Kuis berhasil dibuat. Silakan tambah soal.');
            } else {
                return redirect()->route('adminprogram.quizzes.edit', $quiz->id)
                                 ->with('success', 'Kuis berhasil dibuat. Silakan tambah soal.');
            }

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

        $this->checkAuthorization($quiz->kelas->program_id);

        return view('instructor.quizzes.edit', compact('quiz'));
    }

    /**
     * Update Info Kuis.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $quiz = Quiz::with('kelas.program')->findOrFail($id);

        $this->checkAuthorization($quiz->kelas->program_id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'is_published' => 'sometimes|boolean',
        ]);

        try {
            $quiz->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'max_attempts' => $validated['max_attempts'],
                'is_published' => $validated['is_published'] ?? $quiz->is_published,
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

        $this->checkAuthorization($quiz->kelas->program_id);

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

            // Redirect sesuai role
            if ($user->hasRole('instructor')) {
                return redirect()->route('instructor.kelas.edit', $kelasId)
                                 ->with('success', 'Kuis berhasil dihapus.');
            } else {
                return redirect()->route('adminprogram.kelas.edit', $kelasId)
                                 ->with('success', 'Kuis berhasil dihapus.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus kuis: ' . $e->getMessage());
        }
    }

    /* =============================
     * 3. MANAJEMEN SOAL (QUESTIONS)
     * ============================= */

    /**
     * Simpan Soal Baru
     */
    public function storeQuestion(Request $request, $quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with('kelas.program')->findOrFail($quizId);

        $this->checkAuthorization($quiz->kelas->program_id);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:500',
            'correct_option' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Soal
            $questionData = [
                'quiz_id' => $quizId,
                'question_text' => $validated['question_text'],
            ];

            if ($request->hasFile('image')) {
                $questionData['image_path'] = $request->file('image')->store('quiz-images', 'public');
            }

            $question = Question::create($questionData);

            // 2. Simpan Jawaban (A, B, C, D, E)
            foreach ($validated['options'] as $index => $optionText) {
                Answer::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct' => ($index == $validated['correct_option']),
                ]);
            }

            DB::commit();

            return redirect()->route('instructor.quizzes.edit', $quizId)
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

        $this->checkAuthorization($question->quiz->kelas->program_id);

        return view('instructor.quizzes.partials.question-form-edit', compact('question'));
    }

    /**
     * Update Soal
     */
    public function updateQuestion(Request $request, $questionId)
    {
        $user = Auth::user();
        $question = Question::with(['quiz.kelas.program', 'answers'])->findOrFail($questionId);

        $this->checkAuthorization($question->quiz->kelas->program_id);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'required|array',
            'correct_option' => 'required', // ID dari jawaban yang benar
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Soal
            $data = ['question_text' => $validated['question_text']];

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
            foreach ($validated['options'] as $answerId => $answerText) {
                $isCorrect = ($answerId == $validated['correct_option']);

                Answer::where('id', $answerId)
                      ->where('question_id', $question->id)
                      ->update([
                          'option_text' => $answerText,
                          'is_correct' => $isCorrect
                      ]);
            }

            DB::commit();

            return redirect()->route('instructor.quizzes.edit', $question->quiz_id)
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

        $this->checkAuthorization($question->quiz->kelas->program_id);

        DB::beginTransaction();
        try {
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

    /* =============================
     * 4. METHOD TAMBAHAN
     * ============================= */

    /**
     * Toggle Status Publikasi Kuis
     */
    public function togglePublish($id)
    {
        $user = Auth::user();
        $quiz = Quiz::with('kelas.program')->findOrFail($id);

        $this->checkAuthorization($quiz->kelas->program_id);

        $quiz->update([
            'is_published' => !$quiz->is_published
        ]);

        $status = $quiz->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Kuis berhasil $status.");
    }

    /**
     * Preview Kuis
     */
    public function preview($id)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['kelas.program', 'questions.answers'])->findOrFail($id);

        $this->checkAuthorization($quiz->kelas->program_id);

        return view('instructor.quizzes.preview', compact('quiz'));
    }

    /**
     * Reorder Questions
     */
    public function reorderQuestions(Request $request, $quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with('kelas.program')->findOrFail($quizId);

        $this->checkAuthorization($quiz->kelas->program_id);

        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order' => 'required|integer|min:1'
        ]);

        foreach ($request->questions as $questionData) {
            Question::where('id', $questionData['id'])
                    ->where('quiz_id', $quizId)
                    ->update(['order' => $questionData['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan soal berhasil diperbarui.']);
    }
}
