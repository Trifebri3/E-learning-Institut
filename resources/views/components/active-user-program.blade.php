@props([])

@php
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$now = now();

// Ambil semua program yang user ikuti (enrolled)
$enrolledPrograms = $user->programs()->get();
$totalPrograms = $enrolledPrograms->count();
$displayLimit = 3; // Tampilkan maksimal 3 program
@endphp

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center text-primary-600 dark:text-primary-400 shadow-sm">
                <i class="fas fa-tasks text-xs"></i>
            </span>
            Program Anda
        </h3>
        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-3 py-1 rounded-full shadow-sm">
            Total: {{ $totalPrograms }}
        </span>
    </div>

    <div class="p-4">
        @if($enrolledPrograms->isNotEmpty())
            <div class="space-y-4">
                @foreach($enrolledPrograms->take($displayLimit) as $program)
                    @php
                        $waktu_mulai = $program->waktu_mulai ? \Carbon\Carbon::parse($program->waktu_mulai) : null;
                        $waktu_selesai = $program->waktu_selesai ? \Carbon\Carbon::parse($program->waktu_selesai) : null;
                        $isActive = $waktu_selesai && $waktu_selesai >= $now;
                        $progress = $program->progress_percentage ?? 0;
                    @endphp

                    <div class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:border-primary-300 dark:hover:border-primary-700 transition-all duration-300 hover:shadow-sm">

                        <div class="flex justify-between items-start gap-3 mb-3">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-primary-600 transition-colors">
                                {{ $program->title }}
                            </h4>

                            @if($isActive)
                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-100 dark:border-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                    Selesai
                                </span>
                            @endif
                        </div>

                        @if($isActive && $progress > 0)
                            <div class="mb-3">
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-[10px] text-gray-400 uppercase font-bold">Progress</span>
                                    <span class="text-xs font-bold text-primary-600">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-primary-600 h-1.5 rounded-full transition-all duration-500 ease-out"
                                         style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-2 border-t border-dashed border-gray-100 dark:border-gray-700 mt-2">
                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                @if($waktu_mulai)
                                    <span class="flex items-center gap-1" title="Mulai">
                                        <i class="far fa-calendar text-gray-400"></i>
                                        {{ $waktu_mulai->format('d M') }}
                                    </span>
                                @endif
                                @if($waktu_selesai)
                                    <span class="flex items-center gap-1" title="Selesai">
                                        <i class="far fa-flag text-gray-400"></i>
                                        {{ $waktu_selesai->format('d M') }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('participant.program.show', $program->id) }}"
                               class="text-xs font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 flex items-center gap-1 transition-colors">
                                {{ $isActive ? 'Lanjutkan' : 'Detail' }}
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($totalPrograms > $displayLimit)
                <div class="mt-4 text-center">
                    <a href="{{ route('participant.progress.index') }}"
                       class="text-xs font-bold text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-1">
                        Lihat Semua Program
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endif

        @else
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                    <i class="far fa-folder-open text-xl"></i>
                </div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Belum ada program</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Anda belum mendaftar program apapun.</p>
                <a href="{{ route('participant.program.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm">
                    Jelajahi Program
                </a>
            </div>
        @endif
    </div>
</div>
