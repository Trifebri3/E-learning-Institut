@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Soal</h1>
        <a href="{{ route('adminprogram.quizzes.edit', $question->quiz->id) }}" class="text-gray-500 hover:text-blue-600">Batal</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('adminprogram.quizzes.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label class="block font-bold mb-2">Pertanyaan</label>
        <textarea name="question_text" class="w-full border rounded p-2 mb-4" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>

        <label class="block font-bold mb-2">Gambar (opsional)</label>
        <input type="file" name="image" class="mb-4">
        @if($question->image_path)
            <img src="{{ asset('storage/' . $question->image_path) }}" alt="Gambar Soal" class="mb-4 max-h-40">
        @endif

        <h3>Pilihan Jawaban</h3>
        @foreach($question->answers as $answer)
            <div class="flex items-center gap-2 mb-2">
                <input type="radio" name="correct_option" value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }} required>
                <span>{{ $answer->option_text }}</span>
                <input type="text" name="options[{{ $answer->id }}]" value="{{ old('options.'.$answer->id, $answer->content) }}" class="border rounded p-1 flex-1" placeholder="Isi jawaban" required>
            </div>
        @endforeach

        <div class="mt-4 text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
