@extends('participant.layouts.app'){{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Kelas Saya - Institut Hijau Indonesia')

@section('page_header')
<div class="mb-6 max-w-4xl mx-auto">

    @if($program)
    <div class="flex items-center space-x-4 mb-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
        <!-- Logo Program -->
        @if($program->logo_path)
            <img src="{{ Storage::url($program->logo_path) }}"
                 class="w-16 h-16 md:w-20 md:h-20 rounded-xl bg-white p-2 shadow-lg"
                 alt="Logo {{ $program->title }}">
        @else
            <img src="{{ asset('images/defaultlogoprogram.svg') }}"
                 class="w-16 h-16 md:w-20 md:h-20 rounded-xl bg-white p-2 shadow-lg"
                 alt="Logo Default {{ $program->title }}">
        @endif

        <!-- Nama Program Besar -->
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $program->title }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Kelas Saya</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress Belajar</span>
            <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $progressPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
            <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500 ease-out"
                 style="width: {{ $progressPercentage }}%"></div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            {{ $finished->count() }} dari {{ $kelas->count() }} kelas telah diselesaikan
        </p>
    </div>
    @else
        <div class="text-center text-gray-500 dark:text-gray-400 py-16">
            <i class="fas fa-info-circle text-4xl mb-4"></i>
            <p class="text-lg">Maaf, Anda belum terdaftar di program apapun.</p>
        </div>
    @endif

</div>

@endsection

@section('content')
<div class="space-y-6">
    <div class="mb-6 max-w-4xl mx-auto">

    @if($program)
    <div class="flex items-center space-x-4 mb-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
        <!-- Logo Program -->
        @if($program->logo_path)
            <img src="{{ Storage::url($program->logo_path) }}"
                 class="w-16 h-16 md:w-20 md:h-20 rounded-xl bg-white p-2 shadow-lg"
                 alt="Logo {{ $program->title }}">
        @else
            <img src="{{ asset('images/defaultlogoprogram.svg') }}"
                 class="w-16 h-16 md:w-20 md:h-20 rounded-xl bg-white p-2 shadow-lg"
                 alt="Logo Default {{ $program->title }}">
        @endif

        <!-- Nama Program Besar -->
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $program->title }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Kelas Saya</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress Belajar</span>
            <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $progressPercentage }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
            <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500 ease-out"
                 style="width: {{ $progressPercentage }}%"></div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            {{ $finished->count() }} dari {{ $kelas->count() }} kelas telah diselesaikan
        </p>
    </div>
    @else
        <div class="text-center text-gray-500 dark:text-gray-400 py-16">
            <i class="fas fa-info-circle text-4xl mb-4"></i>
            <p class="text-lg">Maaf, Anda belum terdaftar di program apapun.</p>
        </div>
    @endif

</div>

    <!-- Controls Section -->
    @if($program && $kelas->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Filter & Sort Controls -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- View Toggle -->
                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button onclick="toggleView('grid')"
                            class="p-2 rounded-md transition-all duration-200 {{ $viewType === 'grid' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        <i class="fas fa-th {{ $viewType === 'grid' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}"></i>
                    </button>
                    <button onclick="toggleView('list')"
                            class="p-2 rounded-md transition-all duration-200 {{ $viewType === 'list' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        <i class="fas fa-list {{ $viewType === 'list' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}"></i>
                    </button>
                </div>

                <!-- Filter Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-filter"></i>
                        <span>
                            @if($filter === 'all') Semua Kelas
                            @elseif($filter === 'ongoing') Berlangsung
                            @else Selesai
                            @endif
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-10">
                        <a href="?filter=all&sort={{ $sort }}&view={{ $viewType }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $filter === 'all' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-layer-group w-5 mr-2"></i>Semua Kelas
                        </a>
                        <a href="?filter=ongoing&sort={{ $sort }}&view={{ $viewType }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $filter === 'ongoing' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-play-circle w-5 mr-2"></i>Berlangsung
                        </a>
                        <a href="?filter=finished&sort={{ $sort }}&view={{ $viewType }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $filter === 'finished' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-check-circle w-5 mr-2"></i>Selesai
                        </a>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-sort"></i>
                        <span>
                            @if($sort === 'asc') Terlama
                            @elseif($sort === 'desc') Terbaru
                            @else Terdekat
                            @endif
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-10">
                        <a href="?filter={{ $filter }}&sort=nearest&view={{ $viewType }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $sort === 'nearest' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-clock w-5 mr-2"></i>Terdekat
                        </a>
                        <a href="?filter={{ $filter }}&sort=desc&view={{ $viewType }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $sort === 'desc' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-arrow-down w-5 mr-2"></i>Terbaru
                        </a>
                        <a href="?filter={{ $filter }}&sort=asc&view={{ $viewType }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $sort === 'asc' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                            <i class="fas fa-arrow-up w-5 mr-2"></i>Terlama
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <span class="flex items-center gap-1">
                    <i class="fas fa-play-circle text-blue-500"></i>
                    <span>{{ $ongoing->count() }} Berlangsung</span>
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>{{ $finished->count() }} Selesai</span>
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Kelas Content -->
    @if(!$program)
    <!-- Empty State - No Program -->
    <div class="text-center py-12 animate-fade-in">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                <i class="fas fa-graduation-cap text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Terdaftar Program</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Anda belum terdaftar di program manapun. Silakan redeem program terlebih dahulu.</p>
            <a href="{{ route('participant.redeem.form') }}"
               class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <i class="fas fa-ticket-alt mr-2"></i>
                Redeem Program
            </a>
        </div>
    </div>

    @elseif($kelas->count() === 0)
    <!-- Empty State - No Classes -->
    <div class="text-center py-12 animate-fade-in">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                <i class="fas fa-book-open text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Kelas</h3>
            <p class="text-gray-500 dark:text-gray-400">Belum ada kelas yang dipublikasikan untuk program ini.</p>
        </div>
    </div>

    @else
    <!-- Kelas Grid/List -->
    <div class="space-y-6">
        @if($filter === 'all' && $ongoing->count() > 0)
        <!-- Ongoing Classes -->
        <div class="animate-slide-up">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-play-circle text-blue-500"></i>
                Kelas Berlangsung ({{ $ongoing->count() }})
            </h2>

            @if($viewType === 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ongoing as $item)
                    @include('participant.kelas.partials.card', ['item' => $item, 'type' => 'ongoing'])
                @endforeach
            </div>
            @else
            <div class="space-y-4">
                @foreach($ongoing as $item)
                    @include('participant.kelas.partials.list-item', ['item' => $item, 'type' => 'ongoing'])
                @endforeach
            </div>
            @endif
        </div>
        @endif

        @if(($filter === 'all' && $finished->count() > 0) || $filter === 'finished')
        <!-- Finished Classes -->
        <div class="animate-slide-up" style="animation-delay: 0.1s;">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                Kelas Selesai ({{ $finished->count() }})
            </h2>

            @if($viewType === 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($finished as $item)
                    @include('participant.kelas.partials.card', ['item' => $item, 'type' => 'finished'])
                @endforeach
            </div>
            @else
            <div class="space-y-4">
                @foreach($finished as $item)
                    @include('participant.kelas.partials.list-item', ['item' => $item, 'type' => 'finished'])
                @endforeach
            </div>
            @endif
        </div>
        @endif

        @if($filter === 'ongoing' && $ongoing->count() === 0)
        <!-- No Ongoing Classes -->
        <div class="text-center py-12 animate-fade-in">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Kelas Berlangsung</h3>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada kelas yang sedang berlangsung saat ini.</p>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
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

@push('styles')
<style>
    /* Animasi Kustom */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.6s ease-out;
    }

    /* Hover Effects */
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px -8px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle View Type
    function toggleView(type) {
        const url = new URL(window.location.href);
        url.searchParams.set('view', type);
        window.location.href = url.toString();
    }

    // Countdown Timer for Ongoing Classes
    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer').forEach(timer => {
            const endTime = new Date(timer.dataset.end).getTime();
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                timer.innerHTML = '<span class="text-red-500">Berakhir</span>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

            let countdownText = '';
            if (days > 0) countdownText += `${days}h `;
            if (hours > 0) countdownText += `${hours}j `;
            countdownText += `${minutes}m`;

            timer.textContent = countdownText;
        });
    }

    // Initialize countdowns
    document.addEventListener('DOMContentLoaded', function() {
        updateCountdowns();
        setInterval(updateCountdowns, 60000); // Update every minute
    });
</script>
@endpush
<link href="{{ asset('css/kelas.css') }}" rel="stylesheet">
<link href="{{ asset('css/kelas-utilities.css') }}" rel="stylesheet">
