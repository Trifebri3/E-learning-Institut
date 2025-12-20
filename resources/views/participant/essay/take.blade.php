@extends('participant.layouts.app')

@section('title', 'Ujian Essay - ' . $submission->exam->title)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="sticky top-0 z-30 bg-gray-50 dark:bg-gray-900 pb-4 pt-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-edit text-primary-600 dark:text-primary-400"></i>
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $submission->exam->title }}</h1>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $exam->questions->count() }} Soal Essay
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-lg w-full md:w-auto justify-center">
                        <i class="fas fa-clock text-red-500 animate-pulse"></i>
                        <span id="timer" class="text-xl font-mono font-bold text-red-600 dark:text-red-400 tabular-nums">--:--:--</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Progress Jawaban</span>
                        <span id="progressText">0%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                        <div id="progressBar" class="bg-primary-600 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('participant.essay.submit', $submission->id) }}" method="POST" id="examForm" class="mt-4">
            @csrf

            <div class="space-y-6">
                @foreach($exam->questions as $index => $q)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                        <div class="flex items-start gap-4 mb-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ $loop->iteration }}
                            </span>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                                        {!! nl2br(e($q->question_text)) !!}
                                    </div>
                                    <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 whitespace-nowrap">
                                        {{ $q->max_score }} Poin
                                    </span>
                                </div>

                                @if($q->image_path)
                                    <div class="mt-4">
                                        <img src="{{ Storage::url($q->image_path) }}"
                                             alt="Lampiran Soal {{ $loop->iteration }}"
                                             class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-600">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pl-12">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jawaban Anda</label>
                            <textarea name="answers[{{ $q->id }}]"
                                      rows="6"
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white placeholder-gray-400 transition-all resize-y text-sm leading-relaxed"
                                      placeholder="Ketik jawaban essay Anda di sini..."
                                      oninput="updateProgress()">{{ $savedAnswers[$q->id] ?? '' }}</textarea>
                            <p class="text-right text-[10px] text-gray-400 mt-2">*Jawaban tersimpan otomatis secara berkala</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-yellow-600 dark:text-yellow-400">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Konfirmasi Pengumpulan</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-md">
                                    Pastikan seluruh soal telah terjawab dengan baik. Jawaban tidak dapat diubah setelah Anda menekan tombol kumpulkan.
                                </p>
                            </div>
                        </div>

                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin mengumpulkan jawaban ini? Aksi ini tidak dapat dibatalkan.')"
                                class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 transition-all hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kumpulkan Jawaban
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@php
    use Carbon\Carbon;
    // Hitung waktu selesai berdasarkan started_at + durasi
    $endTime = Carbon::parse($submission->started_at)->addMinutes($submission->exam->duration_minutes)->toIso8601String();
@endphp

<script>
    // Timer Logic
    const endTime = new Date("{{ $endTime }}").getTime();
    const timerElement = document.getElementById("timer");
    const formElement = document.getElementById("examForm");
    let warningShown = false;

    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance <= 0) {
            timerElement.innerHTML = "00:00:00";
            // Auto submit logic here
            // formElement.submit();
            return;
        }

        // Warning at 5 minutes
        if (distance <= 300000 && !warningShown) {
            timerElement.classList.add('text-red-600', 'animate-pulse');
            warningShown = true;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerElement.innerHTML =
            (hours < 10 ? "0" + hours : hours) + ":" +
            (minutes < 10 ? "0" + minutes : minutes) + ":" +
            (seconds < 10 ? "0" + seconds : seconds);
    }

    // Progress Logic
    function updateProgress() {
        const textareas = document.querySelectorAll('textarea');
        let filled = 0;
        textareas.forEach(t => {
            if(t.value.trim().length > 0) filled++;
        });

        const percent = Math.round((filled / textareas.length) * 100);
        document.getElementById('progressBar').style.width = percent + '%';
        document.getElementById('progressText').innerText = percent + '% Terjawab';
    }

    // Init
    setInterval(updateTimer, 1000);
    updateTimer();
    updateProgress();

    // Prevent accidental leave
    window.onbeforeunload = function() {
        return "Yakin ingin meninggalkan halaman? Jawaban mungkin belum tersimpan.";
    };

    // Allow submit without warning
    formElement.addEventListener('submit', function() {
        window.onbeforeunload = null;
    });
</script>
@endsection
