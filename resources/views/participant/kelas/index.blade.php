@extends('participant.layouts.app')

@section('title', 'Kelas Saya - Institut Hijau Indonesia')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelas Saya</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Akses materi dan jadwal pembelajaran Anda.</p>
                </div>
            </div>

            @if($totalKelas > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm w-full md:w-72">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Total Progress</span>
                    <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $progressPercentage }}%</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-primary-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $progressPercentage }}%"></div>
                </div>
                <div class="mt-2 text-xs text-gray-400 flex justify-between">
                    <span>{{ $totalFinished }} Selesai</span>
                    <span>{{ $totalKelas }} Total</span>
                </div>
            </div>
            @endif
        </div>

        @if($totalKelas > 0)
        <div class="sticky top-20 z-30 mb-8 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border border-gray-200 dark:border-gray-800 rounded-xl p-3 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between gap-3">

                <div class="flex flex-wrap items-center gap-2">

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:border-primary-500 transition-colors">
                            <i class="fas fa-filter text-gray-400"></i>
                            <span>
                                @if($filter === 'all') Semua Status
                                @elseif($filter === 'ongoing') Berlangsung
                                @else Selesai
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs ml-1 text-gray-400"></i>
                        </button>
                        <div x-show="open" class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-50 py-1" style="display: none;">
                            <a href="?filter=all&sort={{ $sort }}&view={{ $viewType }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $filter === 'all' ? 'text-primary-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">Semua</a>
                            <a href="?filter=ongoing&sort={{ $sort }}&view={{ $viewType }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $filter === 'ongoing' ? 'text-primary-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">Berlangsung</a>
                            <a href="?filter=finished&sort={{ $sort }}&view={{ $viewType }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $filter === 'finished' ? 'text-primary-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">Selesai</a>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:border-primary-500 transition-colors">
                            <i class="fas fa-sort-amount-down text-gray-400"></i>
                            <span>
                                @if($sort === 'asc') Terlama
                                @elseif($sort === 'desc') Terbaru
                                @else Terdekat
                                @endif
                            </span>
                        </button>
                        <div x-show="open" class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-50 py-1" style="display: none;">
                            <a href="?filter={{ $filter }}&sort=nearest&view={{ $viewType }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $sort === 'nearest' ? 'text-primary-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">Waktu Terdekat</a>
                            <a href="?filter={{ $filter }}&sort=desc&view={{ $viewType }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $sort === 'desc' ? 'text-primary-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">Terbaru Ditambahkan</a>
                            <a href="?filter={{ $filter }}&sort=asc&view={{ $viewType }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $sort === 'asc' ? 'text-primary-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">Terlama Ditambahkan</a>
                        </div>
                    </div>
                </div>

                <div class="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-lg self-start sm:self-auto">
                    <button onclick="toggleView('grid')" class="p-2 rounded-md transition-all {{ $viewType === 'grid' ? 'bg-white dark:bg-gray-700 shadow text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button onclick="toggleView('list')" class="p-2 rounded-md transition-all {{ $viewType === 'list' ? 'bg-white dark:bg-gray-700 shadow text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            @php
                $kelasGroupedByProgram = $kelas->groupBy(function($item) {
                    return $item->program ? $item->program->nama_program : 'Program Lainnya';
                });
            @endphp

            @foreach($kelasGroupedByProgram as $programName => $programKelas)
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden p-6 md:p-8">

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <span class="w-2 h-8 rounded-full bg-primary-500"></span>
                            {{ $programName }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-5">
                            {{ count($programKelas) }} kelas terdaftar dalam program ini
                        </p>
                    </div>
                    @php
                        $programFinished = $programKelas->filter(fn($item) => $item->isFinished())->count();
                        $programProgress = count($programKelas) > 0 ? round(($programFinished / count($programKelas)) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-900 px-4 py-2 rounded-xl">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center text-xs font-bold text-primary-600 border border-gray-200 dark:border-gray-700">
                            {{ $programProgress }}%
                        </div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Selesai</span>
                    </div>
                </div>

                <div class="space-y-8">
                    @php
                        $ongoingKelas = $programKelas->filter(fn($item) => !$item->isFinished());
                        $upcomingKelas = $ongoingKelas->filter(fn($item) => \Carbon\Carbon::parse($item->tanggal_mulai ?? $item->tanggal)->isFuture());
                        $currentOngoingKelas = $ongoingKelas->filter(fn($item) => !\Carbon\Carbon::parse($item->tanggal_mulai ?? $item->tanggal)->isFuture());
                        $finishedKelas = $programKelas->filter(fn($item) => $item->isFinished());
                    @endphp

                    {{-- 1. Upcoming Classes --}}
                    @if(($filter === 'all' || $filter === 'ongoing') && $upcomingKelas->count() > 0)
                    <div x-data="{ expanded: true }">
                        <button @click="expanded = !expanded" class="flex items-center justify-between w-full group mb-4">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400 flex items-center gap-2">
                                <i class="far fa-calendar-alt"></i> Akan Datang ({{ $upcomingKelas->count() }})
                            </h3>
                            <div class="h-px bg-orange-100 dark:bg-orange-900/30 flex-1 mx-4"></div>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="{'rotate-180': expanded}"></i>
                        </button>

                        <div x-show="expanded" x-collapse>
                            @if($viewType === 'grid')
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($upcomingKelas as $item)
                                        @include('participant.kelas.partials.card', ['item' => $item, 'type' => 'upcoming'])
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($upcomingKelas as $item)
                                        @include('participant.kelas.partials.list-item', ['item' => $item, 'type' => 'upcoming'])
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- 2. Ongoing Classes --}}
                    @if(($filter === 'all' || $filter === 'ongoing') && $currentOngoingKelas->count() > 0)
                    <div x-data="{ expanded: true }">
                        <button @click="expanded = !expanded" class="flex items-center justify-between w-full group mb-4">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400 flex items-center gap-2">
                                <i class="fas fa-play-circle"></i> Sedang Berlangsung ({{ $currentOngoingKelas->count() }})
                            </h3>
                            <div class="h-px bg-primary-100 dark:bg-primary-900/30 flex-1 mx-4"></div>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="{'rotate-180': expanded}"></i>
                        </button>

                        <div x-show="expanded" x-collapse>
                            @if($viewType === 'grid')
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($currentOngoingKelas as $item)
                                        @include('participant.kelas.partials.card', ['item' => $item, 'type' => 'ongoing'])
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($currentOngoingKelas as $item)
                                        @include('participant.kelas.partials.list-item', ['item' => $item, 'type' => 'ongoing'])
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- 3. Finished Classes --}}
                    @if(($filter === 'all' || $filter === 'finished') && $finishedKelas->count() > 0)
                    <div x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="flex items-center justify-between w-full group mb-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 p-2 rounded-lg transition-colors">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Selesai ({{ $finishedKelas->count() }})
                            </h3>
                            <div class="h-px bg-gray-100 dark:bg-gray-700 flex-1 mx-4"></div>
                            <span class="text-xs text-gray-400 mr-2" x-show="!expanded">Klik untuk melihat</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="{'rotate-180': expanded}"></i>
                        </button>

                        <div x-show="expanded" x-collapse>
                            @if($viewType === 'grid')
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-75 hover:opacity-100 transition-opacity">
                                    @foreach($finishedKelas as $item)
                                        @include('participant.kelas.partials.card', ['item' => $item, 'type' => 'finished'])
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-4 opacity-75 hover:opacity-100 transition-opacity">
                                    @foreach($finishedKelas as $item)
                                        @include('participant.kelas.partials.list-item', ['item' => $item, 'type' => 'finished'])
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>

        @if(($filter === 'ongoing' && $totalOngoing === 0) || ($filter === 'finished' && $totalFinished === 0))
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700 mt-8">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center">
                <i class="fas fa-filter text-gray-300 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tidak ditemukan</h3>
            <p class="text-gray-500 dark:text-gray-400">Tidak ada kelas yang sesuai dengan filter saat ini.</p>
            <a href="?filter=all" class="inline-block mt-4 text-primary-600 hover:underline text-sm font-medium">Reset Filter</a>
        </div>
        @endif

        @else
        <div class="text-center py-20">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-sm border border-gray-100 dark:border-gray-700">
                    <i class="fas fa-book text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Kelas</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                    Anda belum terdaftar dalam kelas manapun. Mulailah perjalanan belajar Anda dengan menukarkan kode program.
                </p>
                <a href="{{ route('participant.redeem.form') }}"
                   class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Redeem Program Sekarang
                </a>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
// Clickable Cards Logic
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.hover-lift'); // Pastikan class ini ada di partial card
    cards.forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function(e) {
            const link = this.querySelector('a[href]');
            if (link && !e.target.closest('button') && !e.target.closest('a')) {
                window.location = link.href;
            }
        });
    });
});

function toggleView(type) {
    const url = new URL(window.location.href);
    url.searchParams.set('view', type);
    window.location.href = url.toString();
}

// Countdown Logic (Reusable)
function updateCountdowns() {
    const now = new Date().getTime();

    // Upcoming
    document.querySelectorAll('.upcoming-countdown').forEach(timer => {
        const start = new Date(timer.dataset.start).getTime();
        const diff = start - now;
        timer.textContent = formatTime(diff, 'Akan Dimulai');
    });

    // Ongoing
    document.querySelectorAll('.ongoing-countdown').forEach(timer => {
        const end = new Date(timer.dataset.end).getTime();
        const diff = end - now;
        timer.textContent = formatTime(diff, 'Berakhir');
    });
}

function formatTime(ms, labelEnd) {
    if (ms < 0) return labelEnd;
    const d = Math.floor(ms / (1000 * 60 * 60 * 24));
    const h = Math.floor((ms % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    return d > 0 ? `${d}h ${h}j lagi` : `${h}j ${Math.floor((ms % (1000 * 60 * 60)) / (1000 * 60))}m lagi`;
}

setInterval(updateCountdowns, 60000);
updateCountdowns();
</script>
@endpush
