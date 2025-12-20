@php
    use Carbon\Carbon;

    // Inisialisasi variabel status default
    $status = 'selesai';

    // Pastikan variabel $kelas tersedia dan memiliki properti yang dibutuhkan
    if (isset($kelas) && $kelas->tanggal && $kelas->jam_mulai) {
        $now = Carbon::now();
        $start = Carbon::parse($kelas->tanggal . ' ' . $kelas->jam_mulai);

        // Cek jam selesai, jika null anggap selesai 2 jam kemudian atau handle sesuai logika bisnis
        $end = $kelas->jam_selesai
            ? Carbon::parse($kelas->tanggal . ' ' . $kelas->jam_selesai)
            : $start->copy()->addHours(2);

        if ($now->lt($start)) {
            $status = 'belum';
        } elseif ($now->between($start, $end)) {
            $status = 'berlangsung';
        } else {
            $status = 'selesai';
        }
    }
@endphp

@if (isset($kelas) && $kelas->tipe == 'interaktif' && $kelas->link_zoom)
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Akses Kelas Live</h3>

    @if ($status == 'belum')
        <button disabled class="w-full py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
            <i class="fas fa-lock"></i> Belum Dimulai
        </button>
        <p class="text-[10px] text-center text-gray-400 mt-2">
            Mulai: {{ isset($start) ? $start->format('H:i') : '-' }} WIB
        </p>

    @elseif ($status == 'berlangsung')
        <a href="{{ $kelas->link_zoom }}" target="_blank"
           class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5 animate-pulse">
            <i class="fas fa-video"></i> Masuk Zoom
        </a>
        <p class="text-[10px] text-center text-primary-600 mt-2 font-bold">Sedang Berlangsung</p>

    @else
        <button disabled class="w-full py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
            <i class="fas fa-check-circle"></i> Selesai
        </button>
    @endif
</div>
@endif
