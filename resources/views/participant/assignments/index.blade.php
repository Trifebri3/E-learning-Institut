@extends('participant.layouts.app')

@section('title', 'Tugas Prioritas')

@section('content')
<div class="container mx-auto p-4 md:p-6 lg:p-8 max-w-7xl">

    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center shadow-sm">
                <i class="fas fa-hourglass-half text-lg"></i>
            </span>
            Tugas Prioritas
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
            Daftar tugas yang mendekati tenggat waktu dan perlu segera diselesaikan.
        </p>
    </div>

    @if($urgentAssignments->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-check text-3xl text-green-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Aman! Tidak Ada Tugas Mendesak</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm text-center max-w-md">
                Hebat! Anda telah menyelesaikan tugas-tugas prioritas. Cek tugas lainnya untuk persiapan lebih awal.
            </p>

            <div class="mt-6">
                <a href="{{ route('participant.assignments.all') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm">
                    <i class="fas fa-list mr-2 text-primary-500"></i>
                    Lihat Semua Tugas
                </a>
            </div>
        </div>
    @else
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                </span>
                <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                    Perlu Perhatian
                    <span class="ml-2 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 text-xs px-2.5 py-0.5 rounded-full border border-orange-200 dark:border-orange-800">
                        {{ $urgentAssignments->count() }} Tugas
                    </span>
                </h2>
            </div>

            <a href="{{ route('participant.assignments.all') }}"
               class="text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 flex items-center transition-colors">
                Lihat Semua
                <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($urgentAssignments as $assignment)
                @php
                    $submission = $assignment->userSubmission($user->id);
                    $isUpcoming = \Carbon\Carbon::now()->addDays(3)->greaterThan($assignment->due_date);
                @endphp

                <a href="{{ route('participant.assignments.show', $assignment->id) }}"
                   class="group relative flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:border-orange-300 dark:hover:border-orange-700 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden">

                    <div class="absolute top-0 left-0 w-1.5 h-full bg-orange-400 group-hover:bg-orange-500 transition-colors"></div>

                    <div class="flex justify-between items-start mb-3 pl-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 border border-orange-100 dark:border-orange-800/50">
                            <i class="fas fa-fire mr-1.5"></i> Mendesak
                        </span>

                        <span class="text-xs font-medium text-gray-400 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded border border-gray-100 dark:border-gray-600">
                            {{ $assignment->max_points }} Poin
                        </span>
                    </div>

                    <div class="pl-3 mb-4 flex-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                            {{ $assignment->title }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <i class="fas fa-door-open text-gray-400"></i>
                            {{ $assignment->kelas->program->title }} • {{ $assignment->kelas->title }}
                        </p>
                    </div>

                    <div class="pl-3 pt-3 border-t border-gray-100 dark:border-gray-700/50 mt-auto">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Batas Waktu</span>
                                <span class="text-sm font-semibold text-red-500 dark:text-red-400 flex items-center gap-1.5">
                                    <i class="far fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->diffForHumans() }}
                                </span>
                            </div>
                            <div class="h-8 w-8 rounded-full bg-orange-50 dark:bg-orange-900/30 text-orange-500 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-all">
                                <i class="fas fa-arrow-right text-sm"></i>
                            </div>
                        </div>
                        <div class="text-[10px] text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($assignment->due_date)->format('d F Y, H:i') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
