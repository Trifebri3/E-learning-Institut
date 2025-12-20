@extends('participant.layouts.app')

@section('title', 'Semua Tugas')

@section('content')
<div class="container mx-auto p-4 md:p-6 lg:p-8 max-w-7xl">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                    <i class="fas fa-list-check text-lg"></i>
                </span>
                Semua Tugas Saya
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                Daftar lengkap penugasan dari seluruh program yang Anda ikuti.
            </p>
        </div>
        <a href="{{ route('participant.assignments.index') }}"
           class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-xs"></i>
            Kembali ke Prioritas
        </a>
    </div>

    @if($groupedAssignments->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-300 dark:text-gray-500">
                <i class="fas fa-clipboard-check text-4xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Tidak Ada Tugas</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Anda tidak memiliki tugas aktif saat ini.</p>
        </div>
    @else
        <div class="space-y-8">
            @foreach($groupedAssignments as $programTitle => $assignments)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-primary-500"></i>
                            {{ $programTitle }}
                        </h2>
                        <span class="px-3 py-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-full text-xs font-semibold text-gray-500">
                            {{ count($assignments) }} Tugas
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            @foreach($assignments as $assignment)
                                @php
                                    $submission = $assignment->userSubmission($user->id);
                                    $isSubmitted = $submission !== null;
                                    $isGraded = $isSubmitted && $submission->is_graded;
                                    $isDue = \Carbon\Carbon::now()->greaterThan($assignment->due_date);

                                    // Logic Status Warna (Neutral/Pastel)
                                    if($isGraded) {
                                        $borderColor = 'border-primary-200 dark:border-primary-800';
                                        $bgColor = 'bg-white dark:bg-gray-800'; // Tetap putih agar bersih
                                        $statusText = 'Dinilai';
                                        $statusClass = 'text-primary-600 bg-primary-50 dark:bg-primary-900/30 border-primary-100';
                                    } elseif($isSubmitted) {
                                        $borderColor = 'border-blue-200 dark:border-blue-800';
                                        $bgColor = 'bg-white dark:bg-gray-800';
                                        $statusText = 'Menunggu Nilai';
                                        $statusClass = 'text-blue-600 bg-blue-50 dark:bg-blue-900/30 border-blue-100';
                                    } elseif($isDue) {
                                        $borderColor = 'border-red-200 dark:border-red-800';
                                        $bgColor = 'bg-white dark:bg-gray-800';
                                        $statusText = 'Terlewat';
                                        $statusClass = 'text-red-600 bg-red-50 dark:bg-red-900/30 border-red-100';
                                    } else {
                                        $borderColor = 'border-gray-200 dark:border-gray-700';
                                        $bgColor = 'bg-white dark:bg-gray-800';
                                        $statusText = 'Belum Dikerjakan';
                                        $statusClass = 'text-gray-500 bg-gray-100 dark:bg-gray-700 border-gray-200';
                                    }
                                @endphp

                                <a href="{{ route('participant.assignments.show', $assignment->id) }}"
                                   class="group block p-5 rounded-xl border {{ $borderColor }} {{ $bgColor }} hover:shadow-md transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">

                                    <div class="absolute top-0 left-0 w-1 h-full transition-all duration-300 group-hover:bg-primary-500"></div>

                                    <div class="flex justify-between items-start mb-3 pl-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>

                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded border border-gray-100 dark:border-gray-600">
                                            {{ $assignment->max_points }} Pts
                                        </span>
                                    </div>

                                    <div class="pl-2">
                                        <h3 class="font-bold text-gray-900 dark:text-white line-clamp-1 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $assignment->title }}
                                        </h3>

                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-1.5">
                                                <i class="far fa-calendar-alt {{ $isDue && !$isSubmitted ? 'text-red-500' : '' }}"></i>
                                                <span class="{{ $isDue && !$isSubmitted ? 'text-red-500 font-medium' : '' }}">
                                                    {{ \Carbon\Carbon::parse($assignment->due_date)->translatedFormat('d M Y, H:i') }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <i class="fas fa-door-open"></i>
                                                {{ $assignment->kelas->title }}
                                            </div>
                                        </div>

                                        @if($isGraded)
                                            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                                <span class="text-xs text-gray-400 font-medium">Nilai Akhir</span>
                                                <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                                    {{ $submission->score }} <span class="text-gray-400 text-xs font-normal">/ {{ $assignment->max_points }}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
