@props(['kelas'])

@if($kelas && $kelas->program)
@php
    $program = $kelas->program;
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-4 flex items-center space-x-4">

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

    <!-- Info Program -->
    <div class="flex-1">
        <h3 class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $program->title }}</h3>
        <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 mt-1">{{ $program->description ?? 'Deskripsi program tidak tersedia.' }}</p>
        <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">
            Mulai: {{ $program->tanggal_mulai?->format('d M Y') ?? '-' }} | Selesai: {{ $program->tanggal_selesai?->format('d M Y') ?? '-' }}
        </p>

        <a href="{{ route('participant.program.show', $program->id) }}"
           class="mt-2 inline-block bg-primary-500 text-white px-3 py-1 rounded-lg text-xs md:text-sm hover:bg-primary-600 transition-colors">
            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

</div>
@else
<div class="text-center text-gray-500 dark:text-gray-400 py-4">
    <i class="fas fa-info-circle text-3xl mb-2"></i>
    <p class="text-sm md:text-base">Maaf, Anda belum terdaftar di program apapun.</p>
</div>
@endif
