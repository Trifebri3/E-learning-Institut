@extends('adminprogram.layouts.app')

@section('title', 'Kelola Quiz')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kelola Quiz</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola semua quiz dan penilaian peserta</p>
        </div>
        <a href="{{ route('adminprogram.quiz.create') }}"
           class="flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition shadow-lg hover:shadow-xl">
           <i class="fas fa-plus-circle mr-2"></i>
           Tambah Quiz Baru
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Quiz</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $quizzes->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-eye text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Published</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $quizzes->where('is_published', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Draft</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $quizzes->where('is_published', false)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-100 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-infinity text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Unlimited Attempt</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $quizzes->where('max_attempts', null)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Quiz --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Judul Quiz</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kelas / Program</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Attempt</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($quizzes as $quiz)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $loop->iteration + ($quizzes->currentPage() - 1) * $quizzes->perPage() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ Str::limit($quiz->description, 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <div class="font-medium">{{ $quiz->kelas?->title ?? '-' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $quiz->kelas?->program?->title ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $quiz->duration_minutes }} menit
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $quiz->max_attempts ?? '∞' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($quiz->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                {{-- Edit --}}
                                <a href="{{ route('adminprogram.quiz.edit', $quiz->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition"
                                   title="Edit Quiz">
                                   <i class="fas fa-edit mr-1"></i>
                                   Edit
                                </a>

                                {{-- Submission --}}
                                <a href="{{ route('adminprogram.quiz.submissions', $quiz->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition"
                                   title="Lihat Submission">
                                   <i class="fas fa-list-check mr-1"></i>
                                   Submission
                                </a>

                                {{-- Download PDF --}}
                                <a href="{{ route('adminprogram.quiz.download', $quiz->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition"
                                   title="Download PDF">
                                   <i class="fas fa-download mr-1"></i>
                                   PDF
                                </a>

                                {{-- Kelola Soal --}}
                                <a href="{{ route('adminprogram.quiz.soal.index', $quiz->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition"
                                   title="Kelola Soal">
                                   <i class="fas fa-question-circle mr-1"></i>
                                   Soal
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('adminprogram.quiz.destroy', $quiz->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus quiz \"{{ $quiz->title }}\"? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition"
                                            title="Hapus Quiz">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400 dark:text-gray-500">
                                <i class="fas fa-file-alt text-4xl mb-3"></i>
                                <p class="text-lg font-medium">Belum ada quiz</p>
                                <p class="text-sm mt-1">Buat quiz pertama Anda untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($quizzes->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-600">
            {{ $quizzes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
