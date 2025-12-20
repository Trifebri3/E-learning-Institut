@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">

    <h1 class="text-2xl font-bold mb-4">Edit Soal</h1>
    <div class="flex justify-between items-center mb-4">

    {{-- Kembali ke daftar soal --}}
    <a href="{{ route('instructor.quiz.questions.index', $quiz->id) }}"
        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
        <i class="fas fa-list mr-1"></i> Daftar Soal
    </a>

    <div class="flex gap-2">

        {{-- Tombol Soal Sebelumnya --}}
        @if($previous)
            <a href="{{ route('instructor.quiz.questions.edit', [$quiz->id, $previous->id]) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-arrow-left mr-1"></i> Sebelumnya
            </a>
        @endif

        {{-- Tombol Soal Berikutnya --}}
        @if($next)
            <a href="{{ route('instructor.quiz.questions.edit', [$quiz->id, $next->id]) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Berikutnya <i class="fas fa-arrow-right ml-1"></i>
            </a>
        @endif

    </div>
</div>


<form action="{{ route('instructor.quiz.questions.update', [$question->quiz_id, $question->id]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Pertanyaan --}}
        <div class="mb-4">
            <label class="font-bold">Pertanyaan</label>
            <textarea name="question" rows="4" class="w-full border rounded-lg p-3" required>
                {{ old('question', $question->question_text) }}
            </textarea>
        </div>

        {{-- Jika pilihan ganda --}}
        @if($question->answers->count() > 0)
            <h3 class="font-bold mb-2">Pilihan Jawaban</h3>

            @foreach($question->answers as $idx => $ans)
                <div class="flex items-center mb-3 gap-3">
                    <input type="radio"
                        name="correct_answer"
                        value="{{ $ans->id }}"
                        {{ $ans->is_correct ? 'checked' : '' }}>

                    <input type="text"
                        name="answers[{{ $ans->id }}]"
                        value="{{ $ans->option_text }}"
                        class="w-full border p-2 rounded">
                </div>
            @endforeach
        @endif

        <button class="px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Simpan Perubahan
        </button>

    </form>
</div>
@endsection
