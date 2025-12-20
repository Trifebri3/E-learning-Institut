@extends('participant.layouts.app')

@section('title', 'Hasil Ujian Essay - ' . $submission->exam->title)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $submission->exam->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Diselesaikan pada: {{ $submission->submitted_at_formatted }}
                    </p>
                </div>
            </div>

            <!-- Score & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center">
                    <p class="text-sm text-green-600 dark:text-green-400 font-medium mb-2">Nilai Akhir</p>
                    <p class="text-3xl font-bold text-green-700 dark:text-green-300">
                        {{ $submission->final_score ?? '--' }}
                    </p>
                    @if($submission->final_score && $submission->exam->passing_grade)
                        @php
                            $isPassed = $submission->final_score >= $submission->exam->passing_grade;
                        @endphp
                        <p class="text-sm mt-2 {{ $isPassed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                            <i class="fas {{ $isPassed ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $isPassed ? 'LULUS' : 'TIDAK LULUS' }}
                        </p>
                    @endif
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-2">Status Penilaian</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $submission->status == 'graded' ? 'bg-green-500' : 'bg-orange-500' }}"></span>
                        <p class="text-lg font-semibold {{ $submission->status == 'graded' ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">
                            {{ ucfirst($submission->status) }}
                        </p>
                    </div>
                    @if($submission->status == 'graded')
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Telah dinilai oleh instruktur
                        </p>
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Menunggu penilaian instruktur
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Feedback -->
        @if($submission->admin_feedback)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-5 mb-6">
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-yellow-100 dark:bg-yellow-800 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-comment-alt text-yellow-600 dark:text-yellow-400 text-xs"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">Feedback Instruktur</h3>
                    <p class="text-yellow-700 dark:text-yellow-400 leading-relaxed">
                        {{ $submission->admin_feedback }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Questions & Answers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fas fa-list-ol text-blue-600 dark:text-blue-400 text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Jawaban</h2>
            </div>

            <div class="space-y-6">
                @foreach($submission->answers as $answer)
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-5 hover:border-green-300 dark:hover:border-green-600 transition-colors duration-200">

                    <!-- Question Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $loop->iteration }}
                            </span>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Soal {{ $loop->iteration }}</h3>
                        </div>

                        @if($answer->score !== null && $answer->question)
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium
                                {{ $answer->score >= ($answer->question->max_score * 0.7) ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300' }}">
                                <i class="fas fa-star text-xs"></i>
                                {{ $answer->score }}/{{ $answer->question->max_score }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Question Text -->
                    <div class="mb-4">
                        @if($answer->question)
                            <div class="prose dark:prose-invert max-w-none">
                                {!! nl2br(e($answer->question->question_text)) !!}
                            </div>
                        @else
                            <p class="italic text-red-500 dark:text-red-400 flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                Soal telah dihapus atau tidak ditemukan.
                            </p>
                        @endif
                    </div>

                    <!-- Student Answer -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fas fa-pencil-alt text-green-600 dark:text-green-400"></i>
                            Jawaban Anda:
                        </p>
                        <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                            {!! nl2br(e($answer->answer_text)) !!}
                        </div>
                    </div>

                    <!-- Instructor Notes -->
                    @if($answer->notes)
                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-blue-600 dark:text-blue-400"></i>
                            Catatan Instruktur:
                        </p>
                        <p class="text-blue-600 dark:text-blue-400 text-sm">
                            {{ $answer->notes }}
                        </p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('participant.dashboard') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-[1.02] shadow-sm hover:shadow-md">
                <i class="fas fa-home"></i>
                Kembali ke Dashboard
            </a>

            @if($submission->exam->is_published)

            @endif
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.prose {
    line-height: 1.6;
}
.prose p {
    margin-bottom: 0.5em;
}
</style>
@endpush
