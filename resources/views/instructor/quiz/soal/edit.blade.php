@extends('instructor.layouts.app')

@section('content')
<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Edit Soal: {{ $quiz->title }}</h2>

    <form action="{{ route('instructor.quiz.soal.update', [$quiz->id, $question->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Pertanyaan</label>
            <textarea name="question_text" class="w-full px-3 py-2 border rounded" rows="3">{{ old('question_text', $question->question_text) }}</textarea>
        </div>

        <h4 class="font-medium mb-2">Jawaban</h4>
        @foreach($question->answers as $index => $ans)
        <div class="mb-2 flex items-center gap-2">
            <input type="text" name="answers[{{ $index }}][text]" class="w-full px-3 py-2 border rounded" value="{{ old("answers.$index.text", $ans->text) }}">
            <label>
                <input type="radio" name="correct_answer_index" value="{{ $index }}" @if($ans->is_correct) checked @endif> Benar
            </label>
        </div>
        @endforeach

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mt-4">Update Soal</button>
    </form>
</div>
@endsection
