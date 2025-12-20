{{-- resources/views/components/quiz-card.blade.php --}}
@props(['quiz'])

@php
    $remainingAttempts = $quiz->remainingAttempts();
    $hasAttempts = $quiz->attempts()->where('user_id', auth()->id())->exists();
    $isCompleted = $hasAttempts && $remainingAttempts === 0;
@endphp

<div class="quiz-card bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm p-6 mb-4 border border-blue-100">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
                <h3 class="text-xl font-bold text-gray-800">{{ $quiz->title }}</h3>
                @if($isCompleted)
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Selesai
                    </span>
                @elseif($hasAttempts && $remainingAttempts > 0)
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Dalam Proses
                    </span>
                @endif
            </div>

            @if($quiz->description)
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">{{ $quiz->description }}</p>
            @endif

            <div class="flex flex-wrap gap-6 text-sm text-gray-600 mb-4">
                <div class="flex items-center bg-white px-3 py-2 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ $quiz->duration_minutes }} menit</span>
                </div>

                <div class="flex items-center bg-white px-3 py-2 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Maks {{ $quiz->max_attempts }} percobaan</span>
                </div>

                @if($remainingAttempts > 0)
                    <div class="flex items-center bg-green-100 px-3 py-2 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        <span class="font-medium text-green-700">{{ $remainingAttempts }} percobaan tersisa</span>
                    </div>
                @else
                    <div class="flex items-center bg-red-100 px-3 py-2 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="font-medium text-red-700">Tidak ada percobaan tersisa</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="ml-6 flex flex-col gap-2">
            @if($isCompleted)
                {{-- Tombol Lihat Skor --}}
                <a href="{{ route('participant.quiz.result', $quiz->id) }}"
                   class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 inline-flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lihat Skor
                </a>
            @endif

            @if($quiz->is_published && $remainingAttempts > 0)
                {{-- Tombol Mulai Quiz --}}
                <a href="{{ route('participant.quiz.start', $quiz->id) }}"
                   class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 inline-flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mulai Quiz
                </a>
            @elseif(!$isCompleted)
                {{-- Tombol Tidak Tersedia --}}
                <button
                    class="bg-gray-300 text-gray-600 font-semibold py-2 px-4 rounded-lg inline-flex items-center justify-center cursor-not-allowed shadow-sm text-sm"
                    disabled>
                    @if(!$quiz->is_published)
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Tidak Tersedia
                    @else
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Tidak Dapat Diakses
                    @endif
                </button>
            @endif
        </div>
    </div>
</div>
