@php
    $isFinished = $type === 'finished';
    $isInteractive = $item->tipe == 'interaktif';
    $classDate = \Carbon\Carbon::parse($item->tanggal);
    $isToday = $classDate->isToday();
    $isUpcoming = $classDate->isFuture();
@endphp
<link href="{{ asset('css/kelas.css') }}" rel="stylesheet">
<link href="{{ asset('css/kelas-utilities.css') }}" rel="stylesheet">

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover-lift transition-all duration-300
            {{ $isFinished ? 'opacity-75 grayscale' : '' }}
            {{ $isToday ? 'ring-2 ring-primary-500' : '' }}">

    <!-- Banner Image -->
    <div class="relative">
<img
    src="{{ $item->banner_path
        ? Storage::url($item->banner_path)
        : asset('images/defaultkelas.svg') }}"
    alt="{{ $item->title }}"
    class="w-full h-48 object-cover rounded-lg"
/>

        <!-- Status Badge -->
        <div class="absolute top-3 left-3">
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
            @elseif($isUpcoming)
            <span class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded-full flex items-center gap-1">
                <i class="fas fa-clock"></i>
                <span class="countdown-timer" data-end="{{ $classDate->format('Y-m-d\TH:i:s') }}"></span>
            </span>
            @endif
        </div>

        <!-- Type Badge -->
        <div class="absolute top-3 right-3">
            <span class="px-3 py-1 {{ $isInteractive ? 'bg-purple-500' : 'bg-orange-500' }} text-white text-xs font-medium rounded-full flex items-center gap-1">
                <i class="fas fa-{{ $isInteractive ? 'video' : 'book-open' }}"></i>
                {{ $isInteractive ? 'Interaktif' : 'Materi' }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-5">
        <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-2 line-clamp-2">
            {{ $item->title }}
        </h3>

        <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-calendar w-4 mr-2"></i>
                {{ $classDate->translatedFormat('l, d F Y') }}
            </div>
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-clock w-4 mr-2"></i>
                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB
            </div>
            @if($item->instruktur)
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-user w-4 mr-2"></i>
                {{ $item->instruktur }}
            </div>
            @endif
        </div>

        <!-- Action Button -->
        <div class="flex gap-2">
            <a href="{{ route('participant.kelas.show', $item->id) }}"
               class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-center py-2.5 px-4 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2">
                <i class="fas fa-{{ $isFinished ? 'eye' : 'door-open' }}"></i>
                {{ $isFinished ? 'Lihat Detail' : 'Masuk Kelas' }}
            </a>

            @if(!$isFinished && $isInteractive)
            <button class="bg-green-600 hover:bg-green-700 text-white p-2.5 rounded-lg transition-all duration-200 transform hover:scale-105"
                    title="Siapkan Meeting">
                <i class="fas fa-video"></i>
            </button>
            @endif
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
