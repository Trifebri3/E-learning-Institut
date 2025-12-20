@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Penilaian Peserta: <span class="text-indigo-600 dark:text-indigo-400">{{ $submission->user->name }}</span>
        </h1>
        <a href="{{ route('instructor.essay.submissions', $submission->exam_id) }}" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400">
            ← Kembali ke Submission
        </a>
    </div>

    <div class="space-y-6">
        @foreach($submission->answers as $index => $ans)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Soal #{{ $index + 1 }}</h3>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-gray-700 dark:text-gray-300">{{ $ans->question->question_text }}</p>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="text-md font-medium text-gray-800 dark:text-white mb-2">Jawaban Peserta:</h4>
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-100 dark:border-blue-800">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $ans->answer_text }}</p>
                </div>
            </div>

            <form action="{{ route('instructor.essay.grade.save', $ans->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Nilai</label>
                        <input type="number" name="score" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                               value="{{ $ans->score }}" min="0" placeholder="Masukkan nilai">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Feedback</label>
                        <textarea name="feedback" rows="3" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                  placeholder="Berikan feedback untuk peserta">{{ $ans->feedback }}</textarea>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow">
                    Simpan Nilai
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <form action="{{ route('instructor.essay.grade.finish', $submission->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow">
                Tandai Selesai Penilaian
            </button>
        </form>
    </div>
</div>
@endsection
