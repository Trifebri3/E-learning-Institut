@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Soal</h1>
            <p class="text-gray-500 text-sm">Quiz: {{ $quiz->title }}</p>
        </div>
        <a href="{{ route('instructor.quiz.questions.index', $quiz->id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('instructor.quiz.questions.store', $quiz->id) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Pertanyaan</label>
                <textarea name="question" rows="5" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" required>{{ old('question') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Tipe Soal</label>
                <select name="type" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" required>
                    <option value="multiple_choice" @selected(old('type') == 'multiple_choice')>Pilihan Ganda</option>
                    <option value="essay" @selected(old('type') == 'essay')>Essay</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition">
                    <i class="fas fa-save mr-2"></i> Simpan Soal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
