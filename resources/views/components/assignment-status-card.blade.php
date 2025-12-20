@props(['assignment', 'submission', 'isCompact' => false])

@php
    use Carbon\Carbon;

    // Tentukan status submission
    $isSubmitted = $submission !== null;
    $isGraded = $isSubmitted && $submission->is_graded;
    $isLate = $isSubmitted && $submission->is_late;
    $isDue = Carbon::now()->greaterThan($assignment->due_date);
    $isUpcoming = Carbon::now()->addDays(3)->greaterThan($assignment->due_date) && !$isDue;

    // Konfigurasi Status (Warna Primary-based untuk netralitas, atau warna semantik soft)
    if ($isGraded) {
        $statusText = 'Telah Dinilai';
        $statusColor = 'primary'; // Menggunakan warna tema (hijau pastel)
        $statusIcon = 'fas fa-check-circle';
        $scoreText = $submission->score . '/' . $assignment->max_points;
        $borderColor = 'border-primary-500';
    } elseif ($isSubmitted) {
        $statusText = 'Menunggu Penilaian';
        $statusColor = 'blue';
        $statusIcon = 'fas fa-clock';
        $scoreText = $isLate ? 'Terlambat' : 'Terkumpul';
        $borderColor = 'border-blue-400';
    } elseif ($isDue) {
        $statusText = 'Melewati Batas';
        $statusColor = 'red';
        $statusIcon = 'fas fa-times-circle';
        $scoreText = 'Belum Dikumpulkan';
        $borderColor = 'border-red-400';
    } else {
        $statusText = $isUpcoming ? 'Batas Waktu Dekat' : 'Belum Dikumpulkan';
        $statusColor = $isUpcoming ? 'orange' : 'gray';
        $statusIcon = $isUpcoming ? 'fas fa-exclamation-circle' : 'fas fa-pencil-alt';
        $scoreText = 'Batas: ' . Carbon::parse($assignment->due_date)->format('d M');
        $borderColor = $isUpcoming ? 'border-orange-400' : 'border-gray-300 dark:border-gray-600';
    }
@endphp

<div class="group relative bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700 border-l-4 {{ $borderColor }}">

    <div class="flex flex-col md:flex-row gap-4 md:items-start">

        <div class="hidden md:flex flex-shrink-0 w-12 h-12 rounded-full bg-{{ $statusColor === 'primary' ? 'primary-50 dark:bg-primary-900/30' : ($statusColor . '-50 dark:bg-' . $statusColor . '-900/20') }} items-center justify-center">
            <i class="{{ $statusIcon }} text-xl text-{{ $statusColor === 'primary' ? 'primary-600' : ($statusColor . '-500') }}"></i>
        </div>

        <div class="flex-1 min-w-0">

            <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                    {{ $assignment->title }}
                </h3>

                @if($isUpcoming && !$isSubmitted)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-orange-50 text-orange-600 border border-orange-100">
                    Prioritas
                </span>
                @endif
            </div>

            <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mb-3">
                <span class="flex items-center gap-1.5">
                    <i class="far fa-calendar-alt {{ $isDue ? 'text-red-500' : 'text-gray-400' }}"></i>
                    <span class="{{ $isDue ? 'text-red-500 font-medium' : '' }}">
                        {{ Carbon::parse($assignment->due_date)->translatedFormat('d M Y, H:i') }}
                    </span>
                </span>

                @if($isUpcoming && !$isSubmitted)
                    <span class="text-orange-500 text-xs font-semibold px-1.5 py-0.5 bg-orange-50 rounded">
                        {{ Carbon::parse($assignment->due_date)->diffForHumans() }}
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100 dark:border-gray-700/50 mt-auto">

                <div class="flex items-center gap-2">
                    <div class="md:hidden"> {{-- Mobile Icon --}}
                         <i class="{{ $statusIcon }} text-{{ $statusColor === 'primary' ? 'primary-500' : ($statusColor . '-500') }}"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Status</span>
                        <span class="text-sm font-semibold text-{{ $statusColor === 'primary' ? 'primary-700 dark:text-primary-300' : ($statusColor . '-600 dark:text-' . $statusColor . '-400') }}">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>

                <div class="text-right">
                    @if($isGraded)
                        <span class="text-xs text-gray-400 font-medium uppercase tracking-wider block">Nilai</span>
                        <span class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $submission->score }}<span class="text-sm text-gray-400 font-normal">/{{ $assignment->max_points }}</span></span>
                    @else
                         <span class="text-xs text-gray-400 font-medium uppercase tracking-wider block">Poin Maks</span>
                         <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $assignment->max_points }} Poin</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="w-full md:w-auto mt-2 md:mt-0 md:self-center flex-shrink-0">
             @if(!$isSubmitted && !$isDue)
                <a href="{{ route('participant.assignments.show', $assignment->id) }}"
                   class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    <span>Kerjakan</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            @elseif($isSubmitted && !$isGraded)
                <div class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-xl text-sm font-bold cursor-default">
                    <i class="fas fa-spinner fa-spin text-xs"></i>
                    <span>Menunggu</span>
                </div>
            @elseif($isGraded)
                <a href="{{ route('participant.assignments.show', $assignment->id) }}"
                   class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-primary-600 hover:border-primary-200 rounded-xl text-sm font-bold transition-all">
                    <i class="fas fa-file-alt"></i>
                    <span>Detail</span>
                </a>
            @else
                 <div class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-400 rounded-xl text-sm font-bold cursor-not-allowed">
                    <i class="fas fa-lock text-xs"></i>
                    <span>Tutup</span>
                </div>
            @endif
        </div>

    </div>
</div>
