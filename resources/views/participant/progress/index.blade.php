@extends('participant.layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                    <i class="fas fa-chart-line text-lg"></i>
                </span>
                Progres Belajar
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                Pantau perkembangan dan selesaikan kelas Anda.
            </p>
        </div>

        @if($programs->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-400 dark:text-gray-500">
                    <i class="fas fa-book-open text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Program</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center max-w-md mb-6">
                    Anda belum terdaftar di program pembelajaran apapun saat ini.
                </p>
                <a href="{{ route('participant.program.index') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm">
                    <i class="fas fa-search mr-2"></i>
                    Jelajahi Program
                </a>
            </div>

        @else
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between h-full">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Program Diikuti</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $programs->count() }}</span>
                        <i class="fas fa-graduation-cap text-gray-300 dark:text-gray-600 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between h-full">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Total Kelas</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalKelas }}</span>
                        <i class="fas fa-layer-group text-gray-300 dark:text-gray-600 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between h-full">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Kelas Selesai</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $completedKelas }}</span>
                        <i class="fas fa-check-circle text-green-200 dark:text-green-900/50 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col justify-between h-full">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Rata-rata Progress</div>
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $averageProgress }}%</span>
                        <i class="fas fa-chart-pie text-primary-200 dark:text-primary-900/50 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-6" x-data="{ openProgram: null }">
                @foreach($programs as $index => $program)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-shadow hover:shadow-md">

                        <div class="flex flex-col md:flex-row md:items-center justify-between p-5 gap-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                             @click="openProgram = openProgram === {{ $index }} ? null : {{ $index }}">

                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                                    {{ $program->title }}
                                </h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <i class="fas fa-book mr-1.5"></i> {{ $program->kelas->count() }} Kelas Tersedia
                                </span>
                            </div>

                            <div class="flex items-center gap-3 self-end md:self-auto">
                                <div class="flex gap-2" @click.stop>
                                    <a href="{{ route('participant.progress.print', $program->id) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                       title="Cetak Laporan">
                                        <i class="fas fa-print mr-1.5"></i> Cetak
                                    </a>

                                    <a href="{{ route('participant.progress.preview-pdf', $program->id) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                       title="Download PDF">
                                        <i class="fas fa-file-pdf mr-1.5 text-red-500"></i> PDF
                                    </a>
                                </div>

                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 transition-transform duration-300"
                                     :class="{ 'rotate-180': openProgram === {{ $index }} }">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div x-show="openProgram === {{ $index }}"
                             x-collapse
                             class="border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">

                            <div class="p-5">
                                @if($program->kelas->isEmpty())
                                    <div class="text-center py-8">
                                        <p class="text-gray-400 text-sm italic">Belum ada kelas yang tersedia di program ini.</p>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($program->kelas as $kelas)
                                            <a href="{{ route('participant.progress.show', $kelas->id) }}"
                                               class="group block bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-all duration-200 hover:shadow-sm">

                                                <div class="flex items-start justify-between gap-3 mb-3">
                                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                                        {{ $kelas->title }}
                                                    </h4>
                                                    <i class="fas fa-arrow-right text-gray-300 group-hover:text-primary-500 text-xs mt-1 transition-colors"></i>
                                                </div>

                                                <div class="mt-auto">
                                                    @if($kelas->progress_percentage)
                                                        <div class="flex items-end justify-between mb-1">
                                                            <span class="text-[10px] text-gray-500 font-medium uppercase">Progress</span>
                                                            <span class="text-xs font-bold {{ $kelas->progress_percentage == 100 ? 'text-green-600' : 'text-primary-600' }}">
                                                                {{ $kelas->progress_percentage }}%
                                                            </span>
                                                        </div>
                                                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                                            <div class="h-1.5 rounded-full transition-all duration-500 ease-out {{ $kelas->progress_percentage == 100 ? 'bg-green-500' : 'bg-primary-600' }}"
                                                                 style="width: {{ $kelas->progress_percentage }}%"></div>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center gap-2 text-gray-400">
                                                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5"></div>
                                                            <span class="text-[10px] whitespace-nowrap">0%</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-open first program if only one exists
    document.addEventListener('DOMContentLoaded', function() {
        const programsCount = {{ $programs->count() }};
        if (programsCount === 1) {
            setTimeout(() => {
                const firstToggle = document.querySelector('[x-data] > div:first-child .cursor-pointer');
                if (firstToggle) firstToggle.click();
            }, 100);
        }
    });
</script>
@endpush
