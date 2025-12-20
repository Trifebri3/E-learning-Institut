@extends('participant.layouts.app')

@section('title', 'Quiz - ' . $kelas->nama)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors mb-4">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali
            </a>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                            <i class="fas fa-clipboard-list text-lg"></i>
                        </span>
                        Quiz & Evaluasi
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                        Daftar penilaian untuk kelas <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $kelas->nama }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-gray-700">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        @if ($quizzes->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-300 dark:text-gray-500">
                    <i class="fas fa-clipboard-check text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Quiz</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center max-w-sm mb-6">
                    Saat ini belum ada evaluasi yang tersedia untuk kelas ini.
                </p>
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Kembali ke Materi
                </a>
            </div>
        @else

            @php
                $activeQuizzes = $quizzes->where('is_published', true)->filter(function($quiz) {
                    return $quiz->remainingAttempts() > 0;
                });

                $completedQuizzes = $quizzes->filter(function($quiz) {
                    $hasAttempts = $quiz->attempts()->where('user_id', auth()->id())->exists();
                    $remainingAttempts = $quiz->remainingAttempts();
                    return $hasAttempts && $remainingAttempts === 0;
                });
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Quiz</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $quizzes->count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 flex items-center justify-center">
                        <i class="fas fa-list-ol"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tersedia</p>
                        <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $activeQuizzes->count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-500 flex items-center justify-center">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Selesai</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedQuizzes->count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 text-green-500 flex items-center justify-center">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($quizzes as $quiz)
                    <x-quiz-card :quiz="$quiz" />
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-dashed border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-x-6 gap-y-2 justify-center md:justify-start text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span>Quiz dapat dikerjakan</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        <span>Masih ada sisa percobaan</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                        <span>Quiz selesai / Terkunci</span>
                    </div>
                </div>
            </div>

        @endif
    </div>
</div>
@endsection
