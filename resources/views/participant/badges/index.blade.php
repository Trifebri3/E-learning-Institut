@extends('participant.layouts.app')

@section('title', 'Lencana & Pencapaian Saya')

@section('content')
<div class="container mx-auto p-4 md:p-6 lg:p-8 max-w-7xl">

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                    <i class="fas fa-medal text-lg"></i>
                </span>
                Pencapaian Saya
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                Koleksi lencana dari dedikasi dan keberhasilan program Anda.
            </p>
        </div>

        <a href="{{ route('participant.piagam.index') }}"
           class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-primary-500 hover:text-primary-600 dark:hover:text-primary-400 transition-all shadow-sm group">
            <i class="fas fa-certificate text-gray-400 group-hover:text-primary-500 mr-2 transition-colors"></i>
            <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover:text-primary-700 dark:group-hover:text-primary-300">Lihat Piagam</span>
        </a>
    </div>

    @if ($userBadges->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 opacity-50">
                <img src="{{ asset('images/badgegli.svg') }}" class="w-10 h-10 grayscale opacity-50" alt="Placeholder">
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Lencana</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs text-center">
                Selesaikan program pelatihan Anda untuk mulai mengumpulkan lencana eksklusif.
            </p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @foreach($userBadges as $badge)
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden">

                    <div class="relative pt-6 pb-4 px-4 bg-gray-50/50 dark:bg-gray-900/30 flex items-center justify-center group-hover:bg-primary-50/30 dark:group-hover:bg-primary-900/10 transition-colors">
                        <div class="absolute w-16 h-16 bg-primary-200/20 dark:bg-primary-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <img src="{{ asset('images/badgegli.svg') }}"
                             alt="{{ $badge->title }}"
                             class="w-24 h-24 md:w-28 md:h-28 object-contain drop-shadow-sm transform transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-3">
                    </div>

                    <div class="p-4 flex-1 flex flex-col text-center border-t border-gray-100 dark:border-gray-700/50">
                        <h3 class="text-sm md:text-base font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1 group-hover:text-primary-700 dark:group-hover:text-primary-400 transition-colors">
                            {{ $badge->title }}
                        </h3>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-1" title="{{ $badge->program->title }}">
                            {{ $badge->program->title }}
                        </p>

                        <div class="mt-auto pt-3 border-t border-dashed border-gray-100 dark:border-gray-700">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-0.5">Diraih Pada</p>
                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($badge->pivot->earned_at)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
