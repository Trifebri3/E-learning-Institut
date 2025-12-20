@extends('instructor.layouts.app')

@section('content')
<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Tambah Soal Baru: {{ $quiz->title }}</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('instructor.quiz.soal.store', $quiz->id) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Pertanyaan</label>
            <textarea name="question_text" class="w-full px-3 py-2 border rounded" rows="3" placeholder="Masukkan pertanyaan">{{ old('question_text') }}</textarea>
            @error('question_text')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <h4 class="font-medium mb-2">Jawaban</h4>
        @for($i=0; $i<4; $i++)
        <div class="mb-2 flex items-center gap-2">
            <input type="text" name="answers[{{ $i }}][text]" placeholder="Jawaban {{ $i+1 }}" class="w-full px-3 py-2 border rounded" value="{{ old("answers.$i.text") }}">
            <label class="flex items-center gap-1">
                <input type="radio" name="correct_answer_index" value="{{ $i }}" @if(old('correct_answer_index')==$i) checked @endif>
                Benar
            </label>
        </div>
        @error("answers")
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
        @endfor

        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Simpan Soal
            </button>
            <a href="{{ route('instructor.quiz.soal.index', $quiz->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Kembali ke Daftar Soal
            </a>
        </div>
    </form>
</div>
@endsection
