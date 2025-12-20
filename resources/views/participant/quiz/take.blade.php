@extends('participant.layouts.app')

@section('title', $quiz->title)
@section('content')
<div class="container mx-auto p-4 md:p-6 lg:p-8">
    <div class="mb-6">
        <a href="{{ route('participant.kelas.show', $quiz->kelas_id) }}" class="text-sm text-gray-500 dark:text-gray-300 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelas
        </a>
        <h1 class="text-2xl font-bold mt-2 text-gray-900 dark:text-white">{{ $quiz->title }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $quiz->description }}</p>
    </div>

    <div class="mb-4 flex items-center justify-between gap-4">
        <div class="text-sm text-gray-700 dark:text-gray-300">
            Durasi: {{ $quiz->duration_minutes }} menit · Soal: {{ $quiz->questions->count() }}
        </div>

        <div class="flex items-center gap-3">
            <div id="timer" class="font-mono text-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-3 py-1 rounded"></div>
            <div id="progressText" class="text-sm text-gray-600 dark:text-gray-400">Sisa waktu</div>
        </div>
    </div>

    <form id="quiz-form" method="POST" action="{{ route('participant.quiz.submit', $attempt->id) }}">
        @csrf

        @foreach($questions as $q)
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow mb-4 border dark:border-gray-700">
                <p class="font-semibold mb-2 text-gray-900 dark:text-gray-100">{{ $loop->iteration }}. {!! nl2br(e($q->question_text)) !!}</p>

                @if($q->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $q->image_path) }}" alt="image" class="max-w-full rounded" />
                    </div>
                @endif

                <div class="space-y-2">
                    @foreach($q->answers as $ans)
                        <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $ans->id }}" class="form-radio" />
                            <span>{!! nl2br(e($ans->option_text)) !!}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-between items-center">
            <a href="{{ route('participant.kelas.show', $quiz->kelas_id) }}" class="text-sm text-gray-500 dark:text-gray-300">Batal</a>

            <button id="submitBtn" type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                Selesaikan Ujian
            </button>
        </div>
    </form>
</div>

<script>
    (function(){
        // remainingSeconds di-embed oleh controller
        let remaining = parseInt(@json($remainingSeconds), 10);
        const timerEl = document.getElementById('timer');
        const progressText = document.getElementById('progressText');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('quiz-form');

        function formatTime(s) {
            const m = Math.floor(s/60).toString().padStart(2,'0');
            const sec = Math.floor(s%60).toString().padStart(2,'0');
            return `${m}:${sec}`;
        }

        function tick() {
            if (remaining <= 0) {
                timerEl.textContent = "00:00";
                progressText.textContent = "Waktu habis — Mengumpulkan...";
                autoSubmit();
                return;
            }
            timerEl.textContent = formatTime(remaining);
            remaining--;
            setTimeout(tick, 1000);
        }

        // Prevent double-submit and auto-submit when time habis
        function autoSubmit(){
            // disable button to avoid multiple submits
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

            // submit the form
            form.submit();
        }

        // bind normal submit to disable button once clicked
        form.addEventListener('submit', function(e){
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        });

        // start
        tick();

        // warn user if leaving
        window.addEventListener('beforeunload', function (e) {
            // optional: warn user if still working
            if (remaining > 0 && !submitBtn.disabled) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    })();
</script>
@endsection
