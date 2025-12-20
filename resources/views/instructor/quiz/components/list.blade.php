<div class="mt-4">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-medium text-gray-700 dark:text-white">Daftar Quiz</h3>
        <a href="{{ route('instructor.quiz.create', ['kelas_id' => $kelas->id]) }}"
           class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
           Tambah Quiz
        </a>
    </div>

    @if($quizzes->isEmpty())
        <p class="text-gray-500 dark:text-gray-400">Belum ada quiz untuk kelas ini.</p>
    @else
        <div class="space-y-2">
            @foreach($quizzes as $quiz)
                <div class="border p-3 rounded flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">{{ $quiz->title }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Durasi: {{ $quiz->duration_minutes }} menit
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('instructor.quiz.edit', $quiz->id) }}"
                           class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">Edit</a>
                        <a href="{{ route('instructor.quiz.submissions', $quiz->id) }}"
                           class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Submissions</a>
                        <form action="{{ route('instructor.quiz.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Hapus quiz ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
