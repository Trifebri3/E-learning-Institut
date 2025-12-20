@extends('participant.layouts.app')

@section('title', 'Quiz - ' . $program->nama)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors mb-3">
                        <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        Quiz & Assessment
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        Program: <span class="font-semibold text-gray-900 dark:text-white">{{ $program->nama }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <i class="far fa-calendar-alt text-gray-400"></i>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        @if ($quizzes->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 text-center">
                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-400">
                    <i class="fas fa-clipboard-list text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Quiz</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm max-w-sm mb-6">
                    Saat ini belum ada penilaian yang tersedia untuk program ini.
                </p>
                <a href="{{ url()->previous() }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors text-sm">
                    Kembali
                </a>
            </div>
        @else
            @php
                $activeQuizzes = $quizzes->filter(function($quiz) {
                    return $quiz->is_active && now()->between($quiz->start_time, $quiz->end_time);
                });

                $completedQuizzes = $quizzes->filter(function($quiz) {
                    $submission = $quiz->userSubmissions->first();
                    return $submission && $submission->is_submitted;
                });

                $upcomingQuizzes = $quizzes->filter(function($quiz) {
                    return now()->lessThan($quiz->start_time);
                });
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $quizzes->count() }}</span>
                        <i class="fas fa-list-ol text-gray-300"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Aktif</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $activeQuizzes->count() }}</span>
                        <i class="fas fa-play-circle text-primary-200"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Selesai</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedQuizzes->count() }}</span>
                        <i class="fas fa-check-circle text-green-200"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Akan Datang</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $upcomingQuizzes->count() }}</span>
                        <i class="fas fa-clock text-gray-300"></i>
                    </div>
                </div>
            </div>

            <div class="mb-6 overflow-x-auto hide-scrollbar">
                <div class="flex gap-2 border-b border-gray-200 dark:border-gray-700 pb-1 min-w-max">
                    <button class="filter-tab px-4 py-2 text-sm font-semibold rounded-t-lg transition-all border-b-2 border-primary-600 text-primary-600 bg-primary-50 dark:bg-primary-900/10"
                            data-filter="all">
                        Semua
                    </button>
                    <button class="filter-tab px-4 py-2 text-sm font-medium rounded-t-lg transition-all text-gray-500 hover:text-gray-700 dark:text-gray-400 border-b-2 border-transparent hover:bg-gray-50 dark:hover:bg-gray-800"
                            data-filter="active">
                        Berlangsung
                    </button>
                    <button class="filter-tab px-4 py-2 text-sm font-medium rounded-t-lg transition-all text-gray-500 hover:text-gray-700 dark:text-gray-400 border-b-2 border-transparent hover:bg-gray-50 dark:hover:bg-gray-800"
                            data-filter="upcoming">
                        Akan Datang
                    </button>
                    <button class="filter-tab px-4 py-2 text-sm font-medium rounded-t-lg transition-all text-gray-500 hover:text-gray-700 dark:text-gray-400 border-b-2 border-transparent hover:bg-gray-50 dark:hover:bg-gray-800"
                            data-filter="completed">
                        Selesai
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="quiz-container">
                @foreach($quizzes as $quiz)
                    @php
                        $now = now();
                        $startTime = \Carbon\Carbon::parse($quiz->start_time);
                        $endTime = \Carbon\Carbon::parse($quiz->end_time);
                        $submission = $quiz->userSubmissions->first();

                        $isAvailable = $quiz->is_active && $now->between($startTime, $endTime);
                        $isUpcoming = $now->lessThan($startTime);
                        $isEnded = $now->greaterThan($endTime);
                        $isCompleted = $submission && $submission->is_submitted;

                        // Class Filter Logic
                        $filterClass = 'all ';
                        if ($isAvailable) $filterClass .= 'active ';
                        if ($isUpcoming) $filterClass .= 'upcoming ';
                        if ($isCompleted) $filterClass .= 'completed ';
                        if ($isEnded && !$isCompleted) $filterClass .= 'ended ';
                    @endphp

                    <div class="quiz-item {{ $filterClass }}">
                        {{-- Menggunakan komponen yang sudah ada --}}
                        <x-quiz.card :quiz="$quiz" />
                    </div>
                @endforeach
            </div>

            <div id="no-results" class="hidden text-center py-12 px-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 mb-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada quiz yang sesuai filter ini.</p>
            </div>

            <div class="mt-12 pt-6 border-t border-dashed border-gray-200 dark:border-gray-700">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Keterangan Status</h4>
                <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span>
                        <span>Dapat dikerjakan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-yellow-500 rounded-full"></span>
                        <span>Menunggu waktu</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-gray-400 rounded-full"></span>
                        <span>Selesai / Terkunci</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const quizItems = document.querySelectorAll('.quiz-item');
    const noResults = document.getElementById('no-results');
    const quizContainer = document.getElementById('quiz-container');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            // Update active style
            filterTabs.forEach(t => {
                t.classList.remove('border-primary-600', 'text-primary-600', 'bg-primary-50', 'dark:bg-primary-900/10');
                t.classList.add('border-transparent', 'text-gray-500', 'hover:bg-gray-50');
            });
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:bg-gray-50');
            this.classList.add('border-primary-600', 'text-primary-600', 'bg-primary-50', 'dark:bg-primary-900/10');

            // Logic Filtering
            let visibleCount = 0;
            quizItems.forEach(item => {
                if (filter === 'all' || item.classList.contains(filter)) {
                    item.style.display = 'block';
                    // Optional: Add simple fade in animation
                    item.style.opacity = '0';
                    setTimeout(() => item.style.opacity = '1', 50);
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Toggle No Results
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                quizContainer.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                quizContainer.classList.remove('hidden');
            }
        });
    });
});
</script>

<style>
    /* Hilangkan scrollbar tapi tetap bisa scroll */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .quiz-item {
        transition: opacity 0.3s ease;
    }
</style>
@endpush
