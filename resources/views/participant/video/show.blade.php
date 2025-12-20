@extends('participant.layouts.app')

@section('title', $video->title)

@section('content')
<div class="container mx-auto p-4 md:p-6 lg:p-8">

    <div class="mb-6">
        <a href="{{ route('participant.kelas.show', $video->kelas->id) }}" class="text-sm text-gray-500 hover:text-red-600 flex items-center gap-2 dark:text-gray-400 dark:hover:text-red-400">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelas: {{ $video->kelas->title }}
        </a>
        <h1 class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">{{ $video->title }}</h1>
        <span id="watch-status" class="text-sm mt-1 block @if($isWatched) text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
            @if($isWatched) <i class="fas fa-check-circle mr-1"></i> Sudah ditonton
            @else <i class="fas fa-hourglass-start mr-1"></i> Belum selesai
            @endif
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Video Player (2/3 lebar) -->
        <div class="lg:col-span-2">
            <div class="aspect-w-16 aspect-h-9 w-full bg-black rounded-lg overflow-hidden shadow-2xl">
                <div id="youtube-player"></div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow border dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Deskripsi & Keterangan</h3>
                <p class="text-gray-600 dark:text-gray-400">{!! nl2br(e($video->description)) !!}</p>
            </div>
        </div>

        <!-- Status & Kontrol (1/3 lebar) -->
        <div class="lg:col-span-1 space-y-4">
            <div id="status-box" class="p-6 rounded-lg shadow-md @if($isWatched) bg-green-100 dark:bg-green-900/50 @else bg-red-100 dark:bg-red-900/50 @endif">
                <h4 class="font-bold text-lg @if($isWatched) text-green-800 dark:text-green-300 @else text-red-800 dark:text-red-300 @endif mb-2">Status Pemutaran</h4>

                {{-- Status yang Diperbarui oleh JS --}}
                <p id="current-status" class="text-sm @if($isWatched) text-green-700 dark:text-green-400 @else text-red-700 dark:text-red-400 @endif">
                    @if($isWatched) <i class="fas fa-thumbs-up mr-1"></i> Sudah Tuntas. @else <i class="fas fa-hourglass-start mr-1"></i> Menunggu Pemutaran... @endif
                </p>

                <div id="completion-message" class="mt-3 text-xs text-red-600 dark:text-red-400 font-semibold @if($isWatched) hidden @endif">
                    Tontonan harus mencapai durasi penuh agar **Tombol Selesai** muncul.
                </div>
            </div>

            {{-- Tombol Klik Selesai (Diaktifkan oleh JS) --}}
            <button id="complete-btn"
                    data-video-id="{{ $video->id }}"
                    onclick="markVideoAsComplete()"
                    class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition hidden"
                    >
                <i class="fas fa-check-square mr-2"></i> Klik untuk Konfirmasi Selesai
            </button>
        </div>
    </div>

</div>

<!-- Memuat YouTube API di akhir body untuk performa -->
<script src="https://www.youtube.com/iframe_api"></script>

<script>
    let player;
    const videoId = "{{ $video->youtube_id }}";
    const watched = @json($isWatched);
    const completionThreshold = 0.95;
    let allowCompletion = false;
    let lastTime = 0;
    let seeking = false;
    let videoDuration = 0;
    const completeBtn = document.getElementById('complete-btn');

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            videoId: videoId,
            playerVars: {
                'controls': 1,
                'rel':0,
                'modestbranding':1,
                'autoplay':0, // Nonaktifkan autoplay untuk menghindari masalah browser
                'disablekb': 1, // Menonaktifkan kontrol keyboard
                'iv_load_policy': 3,
                'playsinline': 1
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

function onPlayerReady(event) {
    videoDuration = player.getDuration();
    const iframe = player.getIframe();

    // Atur anti-skip (pointer events)
    if (iframe) {
        iframe.style.pointerEvents = watched ? 'auto' : 'none';
    }

    // Status awal
    if (watched) {
         document.getElementById('current-status').innerHTML =
         '<i class="fas fa-thumbs-up mr-1"></i> Sudah Tuntas. Anda dapat menonton ulang.';
         completeBtn.classList.add('hidden');
    } else {
         document.getElementById('current-status').innerHTML =
         '<i class="fas fa-hourglass-start mr-1"></i> Sedang ditonton (Tidak dapat di-skip).';
    }

    // ========= AUTOPLAY FIX (YouTube modern browser rule) =========
    player.mute();       // WAJIB kalau mau autoplay
    player.playVideo();  // Mulai otomatis
    setTimeout(() => player.unMute(), 1500); // Setelah 1.5 detik, suara kembali

    lastTime = 0;
}


    function onPlayerStateChange(event){
        if(watched || allowCompletion) return; // Tidak perlu cek lagi jika sudah selesai

        if(event.data === YT.PlayerState.PLAYING){
            const currentTime = player.getCurrentTime();

            // Anti-Seek Logic: Cek jika skip lebih dari 5 detik ke depan
            if(currentTime > lastTime + 5 && !seeking && lastTime > 0){
                player.seekTo(lastTime, true);
                document.getElementById('current-status').innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> **Ditolak!** Tidak diizinkan skip.';
            } else {
                document.getElementById('current-status').innerHTML = '<i class="fas fa-play mr-1"></i> Sedang Berlangsung...';
            }

            lastTime = currentTime;
            seeking = false;

        } else if(event.data === YT.PlayerState.PAUSED){
            const currentTime = player.getCurrentTime();
            // Jika jeda terjadi karena perbedaan waktu yang besar, anggap seeking
            if(Math.abs(currentTime - lastTime) > 1 && currentTime !== 0) seeking = true;

        } else if(event.data === YT.PlayerState.ENDED){
            // Video Selesai 100%
            allowCompletion = true;
            showCompletionButton();
        }

        // [PROGRESS CHECK] Cek ambang batas 95% (Fallback)
        if(videoDuration > 0) {
            const progress = player.getCurrentTime() / videoDuration;
            if(progress >= completionThreshold && !allowCompletion){
                allowCompletion = true;
                showCompletionButton();
            }
        }
    }

    // Fungsi untuk memunculkan tombol selesai
    function showCompletionButton() {
        if (!watched && allowCompletion) {
            completeBtn.classList.remove('hidden', 'bg-red-600');
            completeBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            completeBtn.disabled = false;
            completeBtn.innerHTML = '<i class="fas fa-check-square mr-2"></i> Klik untuk Konfirmasi Selesai';

            document.getElementById('current-status').innerHTML = 'Tontonan selesai. Konfirmasi di tombol bawah.';
            document.getElementById('completion-message').innerHTML = 'Konfirmasi penyelesaian video.';

            // Nonaktifkan seek sepenuhnya sampai user mengklik tombol
            const iframe = player.getIframe();
            if (iframe) iframe.style.pointerEvents = 'none';
        }
    }
function markVideoAsComplete() {
    if (watched || !allowCompletion) return;

    completeBtn.disabled = true;
    completeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

    let form = new FormData();
    form.append('_token', document.querySelector('meta[name="csrf-token"]').content);

fetch("/participant/video/{{ $video->id }}/complete", {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    },
    body: form
})

    .then(res => res.json())
    .then(data => {
        console.log(data);
        if (!data.success) throw new Error("Gagal menyimpan");

        document.getElementById('current-status').innerHTML =
            '<i class="fas fa-check-circle mr-1"></i> Tuntas!';

        setTimeout(() => {
            window.location.href = "{{ route('participant.kelas.show', $video->kelas->id) }}";
        }, 1200);
    })
    .catch(err => {
        console.error(err);
        completeBtn.disabled = false;
        completeBtn.innerHTML = "Gagal. Coba Lagi.";
    });
}


</script>
@endsection
