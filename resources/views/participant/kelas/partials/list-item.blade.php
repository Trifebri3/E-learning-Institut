@php
    $isFinished = $type === 'finished';
    $isInteractive = $item->tipe == 'interaktif';
    $classDate = \Carbon\Carbon::parse($item->tanggal);
    $isToday = $classDate->isToday();
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover-lift transition-all duration-300
            {{ $isFinished ? 'opacity-75 grayscale border-l-4 border-l-gray-400' : ($isToday ? 'border-l-4 border-l-primary-500' : 'border-l-4 border-l-transparent') }}">

    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <!-- Thumbnail -->
<div class="flex-shrink-0">
    <img
        src="{{ $item->banner_path
            ? Storage::url($item->banner_path)
            : asset('images/defaultkelas.svg') }}"
        alt="{{ $item->title }}"
        class="w-auto h-24 md:w-32 md:h-20 object-cover rounded-lg"
    >
</div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1 line-clamp-2">
                        {{ $item->title }}
                    </h3>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-calendar w-4"></i>
                            {{ $classDate->translatedFormat('d M Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-clock w-4"></i>
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} WIB
                        </span>
                        @if($item->instruktur)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-user w-4"></i>
                            {{ $item->instruktur }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Status & Type -->
                <div class="flex items-center gap-2">
                    @if($isFinished)
                    <span class="px-3 py-1 bg-gray-500 text-white text-xs font-medium rounded-full flex items-center gap-1">
                        <i class="fas fa-check-circle"></i>
                        Selesai
                    </span>
                    @elseif($isToday)
                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-medium rounded-full flex items-center gap-1 animate-pulse">
                        <i class="fas fa-bolt"></i>
                        Hari Ini!
                    </span>
                    @endif

                    <span class="px-3 py-1 {{ $isInteractive ? 'bg-purple-500' : 'bg-orange-500' }} text-white text-xs font-medium rounded-full flex items-center gap-1">
                        <i class="fas fa-{{ $isInteractive ? 'video' : 'book-open' }}"></i>
                        {{ $isInteractive ? 'Interaktif' : 'Materi' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex-shrink-0">
            <a href="{{ route('participant.kelas.show', $item->id) }}"
               class="bg-primary-600 hover:bg-primary-700 text-white py-2.5 px-6 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-{{ $isFinished ? 'eye' : 'door-open' }}"></i>
                {{ $isFinished ? 'Lihat Detail' : 'Masuk Kelas' }}
            </a>
        </div>
    </div>
</div>
@push('scripts')
<script>
// Pastikan semua card bisa diklik
document.addEventListener('DOMContentLoaded', function() {
    // Add click event to all cards
    document.querySelectorAll('.hover-lift').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            // Cari link di dalam card
            const link = this.querySelector('a[href]');
            if (link && !e.target.closest('button')) {
                window.location = link.href;
            }
        });
    });

    // Add hover effects
    document.querySelectorAll('.hover-lift').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });

        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
});
</script>
@endpush


<link href="{{ asset('css/kelas.css') }}" rel="stylesheet">
<link href="{{ asset('css/kelas-utilities.css') }}" rel="stylesheet">
