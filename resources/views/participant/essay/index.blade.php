@extends('participant.layouts.app')

@section('title', 'Ujian Essay')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                        <i class="fas fa-file-alt text-lg"></i>
                    </span>
                    Ujian Essay
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                    Evaluasi pemahaman Anda melalui soal uraian dan studi kasus.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-folder-open text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $exams->count() }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Ujian</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <i class="fas fa-unlock-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $exams->where('is_published', true)->count() }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tersedia</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                    <i class="fas fa-lock text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $exams->where('is_published', false)->count() }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Belum Dibuka</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @forelse($exams as $exam)
                @php
                    $userAttempt = auth()->user()->essaySubmissions()
                        ->where('essay_exam_id', $exam->id)
                        ->first();

                    $hasAttempt = $userAttempt && $userAttempt->status === 'graded';
                    $score = $hasAttempt ? $userAttempt->final_score : null;
                    $isPassed = $score >= ($exam->passing_grade ?? 60);
                @endphp

                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-lg hover:border-primary-200 dark:hover:border-primary-800">
                    <div class="flex flex-col md:flex-row gap-6">

                        <div class="flex-1 flex gap-4">
                            <div class="hidden sm:flex flex-shrink-0 w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 items-center justify-center text-gray-400 group-hover:text-primary-500 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 transition-colors">
                                <i class="fas fa-feather-alt text-2xl"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $exam->title }}
                                    </h3>

                                    @if($hasAttempt)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $isPassed ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                            {{ $score }} Poin
                                        </span>
                                    @endif
                                </div>

                                @if($exam->instructions)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">
                                        {{ Str::limit($exam->instructions, 150) }}
                                    </p>
                                @endif

                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    <span class="flex items-center gap-1.5">
                                        <i class="far fa-clock text-gray-400"></i>
                                        {{ $exam->duration_minutes }} Menit
                                    </span>
                                    @if($exam->question_count)
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-list-ul text-gray-400"></i>
                                        {{ $exam->question_count }} Soal
                                    </span>
                                    @endif
                                    @if($exam->passing_grade && !$hasAttempt)
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-check-circle text-gray-400"></i>
                                        Min. Lulus: {{ $exam->passing_grade }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 justify-center md:items-end md:min-w-[200px] border-t md:border-t-0 border-gray-100 dark:border-gray-700 pt-4 md:pt-0">

                            @if($exam->is_published)
                                <form action="{{ route('participant.essay.start', $exam->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all transform hover:-translate-y-0.5
                                            {{ $hasAttempt
                                                ? 'bg-white border-2 border-primary-100 text-primary-600 hover:border-primary-500 hover:bg-primary-50'
                                                : 'bg-primary-600 text-white hover:bg-primary-700' }}"
                                            onclick="return confirm('{{ $hasAttempt ? 'Mulai ujian ulang? Progress sebelumnya akan ditimpa.' : 'Waktu akan berjalan segera setelah Anda klik OK. Pastikan Anda sudah siap!' }}')">

                                        @if($hasAttempt)
                                            <i class="fas fa-redo text-xs"></i> Ulangi Ujian
                                        @else
                                            <i class="fas fa-play text-xs"></i> Mulai Kerjakan
                                        @endif
                                    </button>
                                </form>
                            @else
                                <div class="w-full px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-xl text-sm font-bold flex items-center justify-center gap-2 cursor-not-allowed">
                                    <i class="fas fa-lock text-xs"></i> Terkunci
                                </div>
                            @endif

                            @if($hasAttempt)
                                <div class="text-right">
                                    <p class="text-xs text-gray-400">Status Kelulusan</p>
                                    <p class="text-sm font-bold {{ $isPassed ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $isPassed ? 'Lulus' : 'Belum Lulus' }}
                                    </p>
                                    @if($userAttempt->submitted_at)
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($userAttempt->submitted_at)->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-3xl text-gray-300 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Ujian</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs text-center">
                        Tidak ada ujian essay yang tersedia untuk saat ini. Silakan cek kembali nanti.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startButtons = document.querySelectorAll('form[action*="essay.start"] button');
        startButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const hasAttempt = this.textContent.includes('Ulangi');
                if (!confirm(hasAttempt
                    ? 'Mulai ujian ulang? Progress sebelumnya akan ditimpa.'
                    : 'Waktu akan berjalan segera setelah Anda klik OK. Pastikan Anda sudah siap!'
                )) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
