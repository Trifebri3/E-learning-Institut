@extends('participant.layouts.app')

@section('title', 'Daftar Program')

@section('content')

<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">Daftar Program</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Temukan dan ikuti program pengembangan diri yang tersedia.
                </p>
            </div>

            <a href="{{ route('participant.redeem.form') }}"
               class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100 dark:text-gray-900 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                <i class="fas fa-ticket-alt mr-2"></i>
                Redeem Kode
            </a>
        </div>

        {{-- Program Aktif User (Komponen tidak diubah) --}}
        <div class="mb-10">
            <x-active-user-program :program="$activeUserProgram" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 mb-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3 overflow-x-auto pb-2 md:pb-0 hide-scrollbar">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Urutkan:</span>

                    @php
                        $sortOptions = [
                            'latest' => 'Terbaru',
                            'oldest' => 'Terlama',
                            'name_asc' => 'Nama (A-Z)',
                            'name_desc' => 'Nama (Z-A)',
                        ];
                        $currentSort = request('sort', 'latest');
                    @endphp

                    @foreach($sortOptions as $key => $label)
                        <a href="{{ route('participant.program.index', ['sort' => $key] + request()->except('sort')) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap border
                           {{ $currentSort == $key
                                ? 'bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white shadow-sm'
                                : 'bg-transparent border-transparent text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">
                    Menampilkan {{ $programs->count() }} program
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($programs as $program)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">

                    {{-- Banner Image --}}
                    <div class="relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-900">
                        <img src="{{ $program->banner_path ? Storage::url($program->banner_path) : asset('images/defaultbannerprogram.svg') }}"
                             alt="{{ $program->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>

                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-white/90 dark:bg-black/80 text-gray-800 dark:text-white backdrop-blur-sm shadow-sm">
                                {{ $program->status ?? 'Aktif' }}
                            </span>
                        </div>

                        <div class="absolute bottom-4 right-4">
                            <div class="w-12 h-12 rounded-xl bg-white p-1 shadow-lg">
                                <img src="{{ $program->logo_path ? Storage::url($program->logo_path) : asset('images/defaultlogoprogram.svg') }}"
                                     alt="Logo"
                                     class="w-full h-full object-cover rounded-lg">
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex-1 flex flex-col">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 leading-tight group-hover:text-primary-600 transition-colors">
                            {{ $program->title }}
                        </h2>

                        <div class="space-y-3 mb-6">
                            @if($program->tanggal_mulai)
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="w-8 flex justify-center">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                    <span>
                                        {{ \Carbon\Carbon::parse($program->tanggal_mulai)->format('d M') }}
                                        @if($program->tanggal_selesai)
                                            - {{ \Carbon\Carbon::parse($program->tanggal_selesai)->format('d M Y') }}
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if($program->kuota)
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="w-8 flex justify-center">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <span>Kuota: {{ $program->kuota }} Peserta</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('participant.program.show', $program->id) }}"
                               class="block w-full py-3 px-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-bold text-sm rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-300 transition-all text-center group-hover:border-primary-500 group-hover:text-primary-600 dark:group-hover:text-primary-400">
                                Lihat Detail Program
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($programs->hasPages())
            <div class="mt-12">
                {{ $programs->links() }}
            </div>
        @endif

    </div>
</div>

<style>
    /* Utility untuk menyembunyikan scrollbar tapi tetap bisa di-scroll */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

@endsection
