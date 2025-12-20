@props(['exam'])

@php
    // Ambil submission user untuk exam ini
    $userSubmission = \App\Models\EssaySubmission::where('essay_exam_id', $exam->id)
        ->where('user_id', auth()->id())
        ->first();

    $hasSubmission = $userSubmission && $userSubmission->submitted_at;
    $isGraded = $userSubmission && $userSubmission->status === 'graded';
    $score = $isGraded ? $userSubmission->final_score : null;
    $isPassed = $score >= ($exam->passing_grade ?? 60);
@endphp

<div class="group relative bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 hover:border-primary-300 dark:hover:border-primary-700">

    <div class="absolute top-0 left-0 h-full w-1 rounded-l-xl
        {{ $isGraded
            ? ($isPassed ? 'bg-green-500' : 'bg-red-500')
            : ($hasSubmission ? 'bg-yellow-500' : 'bg-gray-300 dark:bg-gray-600') }}">
    </div>

    <div class="pl-3"> <div class="flex justify-between items-start mb-3 gap-3">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                    {{ $exam->title }}
                </h3>

                @if($hasSubmission)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                        <i class="far fa-calendar-check"></i>
                        Dikirim: {{ \Carbon\Carbon::parse($userSubmission->submitted_at)->format('d M Y, H:i') }}
                    </p>
                @endif
            </div>

            @if($isGraded)
                <div class="text-right flex-shrink-0">
                    <span class="block text-lg font-bold {{ $isPassed ? 'text-green-600' : 'text-red-600' }}">
                        {{ $score }}%
                    </span>
                    <span class="text-[10px] uppercase font-bold {{ $isPassed ? 'text-green-500' : 'text-red-500' }}">
                        {{ $isPassed ? 'Lulus' : 'Gagal' }}
                    </span>
                </div>
            @elseif($hasSubmission)
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-100">
                    <i class="fas fa-clock mr-1.5"></i> Menunggu
                </span>
            @endif
        </div>

        <div class="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400 mb-4 border-t border-gray-100 dark:border-gray-700 pt-3">
            <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded">
                <i class="far fa-clock"></i> {{ $exam->duration_minutes }} Menit
            </span>

            @if($exam->question_count)
            <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded">
                <i class="fas fa-list-ol"></i> {{ $exam->question_count }} Soal
            </span>
            @endif

            @if($exam->passing_grade)
            <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded">
                <i class="fas fa-trophy text-orange-400"></i> Min. {{ $exam->passing_grade }}%
            </span>
            @endif
        </div>

        <div class="mt-auto">
            @if($exam->is_published)
                @if($hasSubmission)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @if($isGraded)
                            <a href="{{ route('participant.essay.result', $userSubmission->id) }}"
                               class="flex items-center justify-center gap-2 py-2 px-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                <i class="fas fa-chart-bar text-primary-500"></i> Hasil
                            </a>

                            <form action="{{ route('participant.essay.start', $exam->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Mulai ujian ulang? Progress sebelumnya akan ditimpa.')"
                                        class="w-full flex items-center justify-center gap-2 py-2 px-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                    <i class="fas fa-redo text-orange-500"></i> Ulangi
                                </button>
                            </form>
                        @else
                            <a href="{{ route('participant.essay.preview', $exam->id) }}"
                               class="w-full flex items-center justify-center gap-2 py-2 px-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                <i class="fas fa-eye text-blue-500"></i> Lihat Jawaban
                            </a>
                        @endif
                    </div>
                @else
                    <form action="{{ route('participant.essay.start', $exam->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Waktu akan berjalan segera setelah Anda klik OK. Pastikan Anda sudah siap!')"
                                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-lg text-sm transition-all shadow-sm hover:shadow hover:-translate-y-0.5">
                            <i class="fas fa-play text-xs"></i> Mulai Ujian
                        </button>
                    </form>
                @endif
            @else
                <div class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-400 font-medium rounded-lg text-sm cursor-not-allowed">
                    <i class="fas fa-lock text-xs"></i> Belum Dibuka
                </div>
            @endif
        </div>

    </div>
</div>
