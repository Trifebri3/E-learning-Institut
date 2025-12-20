@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Soal</h1>
            <p class="text-gray-500 text-sm">Quiz: {{ $quiz->title }}</p>
        </div>
        <a href="{{ route('instructor.quiz.questions.index', $quiz->id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('instructor.quiz.questions.update', [$quiz->id, $question->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Pertanyaan</label>
                <textarea name="question" rows="5" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" required>{{ old('question', $question->question) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Tipe Soal</label>
                <select name="type" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" required>
                    <option value="multiple_choice" @selected(old('type', $question->type) == 'multiple_choice')>Pilihan Ganda</option>
                    <option value="essay" @selected(old('type', $question->type) == 'essay')>Essay</option>
                </select>
            </div>

            <div class="flex justify-between items-center pt-4 border-t dark:border-gray-700">
                <button type="button" onclick="document.getElementById('delete-form').submit();" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                    <i class="fas fa-trash mr-1"></i> Hapus Soal
                </button>

                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Form Delete -->
        <form id="delete-form" action="{{ route('instructor.quiz.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" style="display: none;" onsubmit="return confirm('Yakin ingin menghapus soal ini?');">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
