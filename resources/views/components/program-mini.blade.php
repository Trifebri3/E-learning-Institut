@php
use Carbon\Carbon;

$completedPrograms = $enrolledPrograms->filter(function($program) {
    return Carbon::parse($program->waktu_selesai)->isPast();
})->sortByDesc('waktu_selesai');

function hasEarnedBadge($user, $program) {
    $totalKelas = $program->kelas()->where('is_published', true)->count();
    $totalHadirFull = \App\Models\PresensiHasil::where('user_id', $user->id)
                            ->whereIn('kelas_id', $program->kelas->pluck('id'))
                            ->where('status_kehadiran', 'hadir_full')
                            ->count();
    return $totalKelas > 0 && $totalKelas == $totalHadirFull;
}
@endphp

<div class="mt-8">

    <div class="mb-6 flex items-center justify-between px-2">
        <div>
            <h3 class="text-xl font-bold text-primary-800 dark:text-white flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-800 flex items-center justify-center text-primary-600 dark:text-primary-300">
                    <i class="fas fa-history text-sm"></i>
                </span>
                Riwayat Program
            </h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 ml-10">
                Arsip program yang telah diselesaikan
            </p>
        </div>

        @if($completedPrograms->count() > 0)
        <span class="px-3 py-1 bg-primary-50 dark:bg-primary-900 border border-primary-200 dark:border-primary-700 text-primary-700 dark:text-primary-300 rounded-full text-xs font-bold">
            {{ $completedPrograms->count() }} Selesai
        </span>
        @endif
    </div>

    <div class="relative w-full">
        <div class="absolute left-0 top-0 bottom-0 w-4 bg-gradient-to-r from-gray-50 dark:from-gray-900 to-transparent z-10 pointer-events-none md:hidden"></div>
        <div class="absolute right-0 top-0 bottom-0 w-4 bg-gradient-to-l from-gray-50 dark:from-gray-900 to-transparent z-10 pointer-events-none md:hidden"></div>

        <div class="flex flex-nowrap gap-5 pb-8 overflow-x-auto scrollbar-hide snap-x snap-mandatory px-2" style="-webkit-overflow-scrolling: touch;">

            @foreach($completedPrograms as $program)
                @php
                    $earned = hasEarnedBadge($user, $program);
                    $badgeImage = $earned && $program->badgeTemplate && $program->badgeTemplate->image_path
                                  ? Storage::url($program->badgeTemplate->image_path)
                                  : null;
                @endphp

                <div class="group relative flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-primary-500/10 border border-primary-100 dark:border-primary-800/50 p-5 flex-shrink-0 w-[280px] md:w-[300px] snap-center transition-all duration-300 hover:-translate-y-1">

                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/3 h-1 bg-primary-200 dark:bg-primary-700 rounded-b-full group-hover:bg-primary-500 transition-colors"></div>

                    <div class="w-20 h-20 mx-auto flex-shrink-0 bg-primary-50 dark:bg-gray-700 rounded-2xl border border-primary-100 dark:border-gray-600 p-2 overflow-hidden shadow-inner group-hover:scale-105 transition-transform duration-300">
                        @if($program->logo_path)
                            <img src="{{ Storage::url($program->logo_path) }}"
                                 alt="Logo {{ $program->title }}"
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-primary-300">
                                <i class="fas fa-leaf text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 text-center flex-1">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white line-clamp-2 leading-snug group-hover:text-primary-600 transition-colors">
                            {{ $program->title }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-1">
                            Selesai: {{ Carbon::parse($program->waktu_selesai)->format('d M Y') }}
                        </p>
                    </div>

                    @if($earned)
                        <div class="mt-4 pt-4 border-t border-dashed border-primary-100 dark:border-gray-700 flex items-center gap-3 bg-primary-50/50 dark:bg-gray-700/30 -mx-5 -mb-5 p-3 rounded-b-2xl">
                            <div class="w-10 h-10 flex-shrink-0 rounded-full border-2 border-white dark:border-gray-600 shadow-sm overflow-hidden bg-white">
                                <img src="{{ $badgeImage }}"
                                     alt="Badge"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="text-left">
                                <p class="text-[10px] font-bold text-primary-600 dark:text-primary-300 uppercase tracking-wider">Achievement</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">Badge Terverifikasi</p>
                            </div>
                            <div class="ml-auto text-primary-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    @else
                        <div class="mt-4 pt-3 border-t border-primary-100 dark:border-gray-700 text-center">
                            <span class="inline-flex items-center text-xs font-medium text-gray-500">
                                <i class="fas fa-flag-checkered mr-1.5"></i> Program Tamat
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @if($completedPrograms->isEmpty())
    <div class="flex flex-col items-center justify-center py-10 px-6 bg-white dark:bg-gray-800 rounded-3xl border-2 border-dashed border-primary-100 dark:border-gray-700 text-center">
        <div class="w-16 h-16 bg-primary-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-primary-300">
            <i class="fas fa-seedling text-3xl"></i>
        </div>
        <h4 class="text-gray-900 dark:text-white font-bold mb-1">Belum Ada Riwayat</h4>
        <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs mx-auto">
            Selesaikan program Anda untuk melihat riwayat dan koleksi badge di sini.
        </p>
    </div>
    @endif

</div>

<style>
    /* Utility Classes */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
        scroll-behavior: smooth;
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }

    /* Smooth Line Clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
