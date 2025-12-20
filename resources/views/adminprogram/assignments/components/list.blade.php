@props(['kelas', 'assignments'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 mt-6">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tugas & Ujian - {{ $kelas->name }}</h3>
        <a href="{{ route('adminprogram.assignments.create', ['kelas_id' => $kelas->id]) }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow text-sm">
            <i class="fas fa-plus mr-2"></i>
            Tambah Tugas
        </a>
    </div>

    <div class="p-6">
        @if($assignments->count() == 0)
            <div class="text-center py-8">
                <div class="text-gray-400 dark:text-gray-500 mb-3">
                    <i class="fas fa-tasks text-4xl"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Belum ada tugas untuk kelas ini</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($assignments as $assignment)
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex justify-between items-center">
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-white">{{ $assignment->title }}</h4>
<p class="text-sm text-gray-500 dark:text-gray-400">
    Deadline: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y H:i') }}
</p>

                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('adminprogram.assignments.edit', $assignment->id) }}"
                           class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">
                            Edit
                        </a>
                        <a href="{{ route('adminprogram.assignments.submissions', $assignment->id) }}"
                           class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-sm">
                            Submissions
                        </a>
                        <form action="{{ route('adminprogram.assignments.destroy', $assignment->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus tugas ini?')"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
