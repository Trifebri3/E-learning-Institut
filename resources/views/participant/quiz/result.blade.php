@extends('participant.layouts.app')

@section('title', 'Hasil Quiz - ' . $attempt->quiz->title)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('participant.dashboard') }}"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors mb-4">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Dashboard
            </a>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                        Hasil Pengerjaan
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        Detail evaluasi untuk <span class="font-semibold text-gray-900 dark:text-white">{{ $attempt->quiz->title }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400 uppercase tracking-wider font-bold">Tanggal Selesai</span>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($attempt->finished_at)->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">

            <div class="p-8">
                <div class="flex flex-col items-center justify-center mb-10">
                    <div class="relative w-40 h-40 mb-6">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" stroke="#f3f4f6" stroke-width="8" fill="none" class="dark:stroke-gray-700"/>

                            @php
                                $percentage = $attempt->score;
                                $passingGrade = $attempt->quiz->passing_grade ?? 60;
                                $isPassed = $percentage >= $passingGrade;
                                $strokeColor = $isPassed ? '#10b981' : '#ef4444'; // Green or Red
                                $circumference = 2 * 3.14159 * 45;
                                $strokeDashoffset = $circumference - ($percentage / 100) * $circumference;
                            @endphp
                            <circle cx="50" cy="50" r="45"
                                    stroke="{{ $strokeColor }}"
                                    stroke-width="8"
                                    fill="none"
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $strokeDashoffset }}"
                                    stroke-linecap="round"
                                    class="transition-all duration-1500 ease-out"/>
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $attempt->score }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Nilai</span>
                        </div>
                    </div>

                    <div class="text-center">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold border
                            {{ $isPassed
                                ? 'bg-green-50 text-green-700 border-green-100 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800'
                                : 'bg-red-50 text-red-700 border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800' }}">
                            <i class="fas {{ $isPassed ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                            {{ $isPassed ? 'LULUS' : 'TIDAK LULUS' }}
                        </span>
                        <p class="text-xs text-gray-400 mt-3">
                            Ambang Batas Kelulusan: <span class="font-bold text-gray-600 dark:text-gray-300">{{ $passingGrade }}</span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-gray-100 dark:border-gray-700 pt-8">
                    @php
                        $questions = $attempt->quiz->questions ?? collect();
                        $answers   = $attempt->quizAnswers ?? collect();
                        $totalQuestions = $questions->count();
                        $correctAnswers = $answers->where('is_correct', true)->count();
                        $wrongAnswers   = max($totalQuestions - $correctAnswers, 0);
                        $accuracy = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

                        $start = \Carbon\Carbon::parse($attempt->started_at);
                        $end   = \Carbon\Carbon::parse($attempt->finished_at);
                        $duration = $start->diff($end);
                        $durationText = ($duration->i > 0 ? $duration->i . 'm ' : '') . $duration->s . 's';
                    @endphp

                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 text-center border border-gray-100 dark:border-gray-600">
                        <span class="block text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $totalQuestions }}</span>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Soal</span>
                    </div>

                    <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-center border border-green-100 dark:border-green-800">
                        <span class="block text-2xl font-bold text-green-600 dark:text-green-400 mb-1">{{ $correctAnswers }}</span>
                        <span class="text-xs font-medium text-green-700 dark:text-green-300 uppercase">Benar</span>
                    </div>

                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-center border border-red-100 dark:border-red-800">
                        <span class="block text-2xl font-bold text-red-600 dark:text-red-400 mb-1">{{ $wrongAnswers }}</span>
                        <span class="text-xs font-medium text-red-700 dark:text-red-300 uppercase">Salah</span>
                    </div>

                    <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-center border border-blue-100 dark:border-blue-800">
                        <span class="block text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">{{ $durationText }}</span>
                        <span class="text-xs font-medium text-blue-700 dark:text-blue-300 uppercase">Durasi</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900/50 p-6 md:p-8 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1 text-center md:text-left">
                        @if(!$isPassed)
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1 flex items-center justify-center md:justify-start gap-2">
                                <i class="fas fa-lightbulb text-yellow-500"></i> Saran Perbaikan
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Jangan menyerah! Pelajari kembali materi terkait dan coba lagi untuk mendapatkan hasil yang lebih baik.
                            </p>
                        @else
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1 flex items-center justify-center md:justify-start gap-2">
                                <i class="fas fa-medal text-primary-500"></i> Kerja Bagus!
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Anda telah menunjukkan pemahaman yang baik. Pertahankan semangat belajar Anda untuk materi berikutnya.
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <a href="{{ route('participant.dashboard') }}"
                           class="inline-flex justify-center items-center px-5 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>

                        @if($attempt->quiz->allow_review)
                            <a href="#"
                               class="inline-flex justify-center items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i> Review Jawaban
                            </a>
                        @else
                             <a href="{{ route('participant.quiz.show', $attempt->quiz->id) }}"
                               class="inline-flex justify-center items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                <i class="fas fa-redo mr-2"></i> Coba Lagi
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
