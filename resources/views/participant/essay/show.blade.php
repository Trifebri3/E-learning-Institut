@if($submission && $submission->status != 'ongoing')
    <!-- Tombol Lihat Hasil (jika sudah selesai) -->
    <a href="{{ route('participant.essay.result', $submission->id) }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-[1.02] shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
        <i class="fas fa-chart-line text-sm"></i>
        {{ $buttonText ?? 'Lihat Hasil' }}
    </a>
@else
    <!-- Tombol Mulai/Ulangi Ujian -->
    <form action="{{ route('participant.essay.start', $exam->id) }}" method="POST">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-[1.02] shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                onclick="return confirm('{{ $submission ? 'Mulai ujian ulang? Progress sebelumnya akan ditimpa.' : 'Waktu akan berjalan segera setelah Anda klik OK. Pastikan Anda sudah siap!' }}')">
            <i class="fas {{ $submission ? 'fa-redo' : 'fa-play-circle' }} text-sm"></i>
            {{ $buttonText ?? ($submission ? 'Ulangi Ujian' : 'Mulai Ujian') }}
        </button>
    </form>
@endif
