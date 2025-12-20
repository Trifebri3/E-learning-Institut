@props(['quizzes'])

@if($quizzes->isNotEmpty())
    <div class="mt-8 p-6 bg-indigo-50 dark:bg-gray-800/80 shadow-inner rounded-xl border border-indigo-100 dark:border-gray-700">
        <h3 class="text-xl font-bold text-indigo-900 dark:text-indigo-300 mb-4 flex items-center">
            <i class="fas fa-clipboard-question mr-3"></i> Ujian & Kuis
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($quizzes as $quiz)
                @php
                    $user = Auth::user();
                    // Ambil attempt terakhir user
                    $lastAttempt = $quiz->attempts()
                                        ->where('user_id', $user->id)
                                        ->whereNotNull('finished_at')
                                        ->orderByDesc('created_at')
                                        ->first();

                    // Hitung sisa kesempatan
                    $usedAttempts = $quiz->attempts()->where('user_id', $user->id)->count();
                    $remaining = max(0, $quiz->max_attempts - $usedAttempts);
                    $isFinished = $remaining === 0;
                @endphp

                <a href="{{ route('participant.quiz.show', $quiz->id) }}"
                   class="block p-4 rounded-lg transition duration-200 border-l-4 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md
                          @if($lastAttempt && $lastAttempt->score >= 70) border-green-500
                          @elseif($lastAttempt) border-yellow-500
                          @else border-indigo-500 @endif">

                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                {{ $quiz->title }}
                            </h4>
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex flex-col gap-1">
                                <span><i class="fas fa-clock mr-1"></i> {{ $quiz->duration_minutes }} Menit</span>
                                <span><i class="fas fa-sync mr-1"></i> Kesempatan: {{ $remaining }}x lagi</span>
                            </div>
                        </div>

                        <!-- Badge Status Nilai -->
                        <div class="text-right">
                            @if($lastAttempt)
                                <span class="block text-2xl font-bold @if($lastAttempt->score >= 70) text-green-600 @else text-yellow-600 @endif">
                                    {{ (int)$lastAttempt->score }}
                                </span>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Nilai Terakhir</span>
                            @else
                                <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-bold">
                                    BARU
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
