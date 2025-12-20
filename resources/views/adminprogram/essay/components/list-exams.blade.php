{{-- resources/views/adminprogram/essay/components/list-exams.blade.php --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 mt-6">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Ujian Essay Program</h3>
        <a href="{{ route('adminprogram.essay.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow text-sm">
            <i class="fas fa-plus mr-2"></i>
            Buat Ujian Baru
        </a>
    </div>

    <div class="p-6">
        @if($exams->count() == 0)
            <div class="text-center py-8">
                <div class="text-gray-400 dark:text-gray-500 mb-3">
                    <i class="fas fa-file-alt text-4xl"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Belum ada ujian essay untuk program ini</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($exams as $exam)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="text-lg font-medium text-gray-800 dark:text-white">{{ $exam->title }}</h4>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Dibuat: {{ \Carbon\Carbon::parse($exam->created_at)->format('d M Y') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-list-ol text-gray-400 dark:text-gray-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Soal</p>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $exam->questions->count() }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <i class="fas fa-users text-gray-400 dark:text-gray-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Peserta</p>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $exam->submissions->count() }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 dark:text-gray-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Durasi</p>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $exam->duration_minutes }} menit</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <i class="fas fa-tasks text-gray-400 dark:text-gray-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    @if($exam->submissions->whereNull('submitted_at')->count() > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Belum Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('adminprogram.essay.edit', $exam->id) }}"
                               class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded shadow">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>

                            <a href="{{ route('adminprogram.essay.questions', $exam->id) }}"
                               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow">
                                <i class="fas fa-list-ol mr-1"></i>
                                Soal
                            </a>

                            <a href="{{ route('adminprogram.essay.submissions', $exam->id) }}"
                               class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow">
                                <i class="fas fa-users mr-1"></i>
                                Submission
                            </a>

                            <form action="{{ route('adminprogram.essay.destroy', $exam->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Hapus ujian ini?')"
                                        class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded shadow">
                                    <i class="fas fa-trash mr-1"></i>
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
