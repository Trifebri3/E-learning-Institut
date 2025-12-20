@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Soal Quiz</h1>
            <p class="text-gray-500 text-sm">Quiz: {{ $quiz->title }}</p>
        </div>
        <a href="{{ route('instructor.quiz.questions.create', $quiz->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Soal
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
        @if($quiz->questions->isEmpty())
            <p class="text-gray-500">Belum ada soal untuk quiz ini.</p>
        @else
            <ul class="space-y-2">
                @foreach($quiz->questions as $question)
                    <li class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition">
                        <div>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $loop->iteration }}. {{ $question->question }}</span>
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full {{ $question->type == 'essay' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $question->type == 'essay' ? 'Essay' : 'Pilihan Ganda' }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('instructor.quiz.questions.edit', [$quiz->id, $question->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('instructor.quiz.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" onsubmit="return confirm('Hapus soal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-1">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
