@extends('participant.layouts.app')

@section('title', 'Piagam Saya')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                        <i class="fas fa-certificate text-lg"></i>
                    </span>
                    Piagam Penghargaan
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                    Dokumen resmi bukti penyelesaian program Anda.
                </p>
            </div>

            <a href="{{ route('participant.badges.index') }}"
               class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-primary-500 hover:text-primary-600 dark:hover:text-primary-400 transition-all shadow-sm group">
                <i class="fas fa-medal text-gray-400 group-hover:text-primary-500 mr-2 transition-colors"></i>
                <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover:text-primary-700 dark:group-hover:text-primary-300">Lihat Lencana</span>
            </a>
        </div>

        @if($piagam->count())
            <div class="mb-12">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-primary-500 rounded-full"></div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Koleksi Piagam</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($piagam as $item)
                        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:-translate-y-1 relative">

                            <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-600"></div>

                            <div class="p-6">
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2 leading-snug group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $item->program->title ?? 'Program Tidak Diketahui' }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1.5">
                                        <i class="far fa-calendar-check text-primary-500"></i>
                                        Terbit: {{ $item->issued_at->translatedFormat('d M Y') }}
                                    </p>
                                </div>

                                <div class="space-y-2 py-4 border-t border-dashed border-gray-100 dark:border-gray-700">
                                    @if($item->grade)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Predikat</span>
                                            <span class="font-bold text-gray-800 dark:text-white">{{ $item->grade }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">No. Seri</span>
                                        <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-600 dark:text-gray-300">{{ $item->serial_number }}</span>
                                    </div>
                                </div>

                                <a href="#" class="block w-full py-2.5 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 text-center font-bold text-sm rounded-xl hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors">
                                    <i class="fas fa-download mr-1"></i> Unduh Piagam
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($userPrograms->count())
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-1 h-6 bg-gray-400 rounded-full"></div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Status Pengajuan</h2>
                </div>

                <div class="space-y-4">
                    @foreach($userPrograms as $program)
                        @php
                            $programPiagam = $piagamMap->get($program->id);
                        @endphp

                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:border-gray-300 dark:hover:border-gray-600 transition-colors">

                            <div class="flex-1">
                                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                                    {{ $program->title }}
                                </h4>
                                <div class="flex items-center gap-2">
                                    @if($programPiagam && $programPiagam->is_approved)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                        </span>
                                    @elseif($programPiagam)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-yellow-600 dark:text-yellow-400">
                                            <i class="fas fa-clock"></i> Dalam Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500">
                                            <i class="fas fa-circle text-[6px]"></i> Belum Diajukan
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="w-full md:w-auto flex-shrink-0">
                                @if(!$programPiagam)
                                    <form action="{{ route('participant.piagam.request', $program->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full md:w-auto px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all hover:shadow-md">
                                            Ajukan Piagam
                                        </button>
                                    </form>
                                @elseif($programPiagam->is_approved)
                                    <a href="{{ route('participant.piagam.preview', $programPiagam->id) }}" target="_blank"
                                       class="flex items-center justify-center w-full md:w-auto px-5 py-2 border border-primary-200 text-primary-700 hover:bg-primary-50 dark:border-primary-800 dark:text-primary-300 dark:hover:bg-primary-900/20 text-sm font-bold rounded-lg transition-colors">
                                        <i class="fas fa-print mr-2"></i> Cetak
                                    </a>
                                @else
                                    <button disabled class="w-full md:w-auto px-5 py-2 bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 text-sm font-bold rounded-lg cursor-not-allowed">
                                        Menunggu
                                    </button>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!$piagam->count() && !$userPrograms->count())
            <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                <div class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6 border border-gray-100 dark:border-gray-700">
                    <i class="fas fa-certificate text-4xl text-gray-300 dark:text-gray-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Piagam</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                    Selesaikan program pelatihan Anda untuk mulai mengumpulkan piagam penghargaan.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
