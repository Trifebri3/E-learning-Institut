@extends('adminprogram.layouts.app')

@section('title', 'Pilih Program - E-Raport')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">E-Raport</h1>
        <p class="text-gray-600 dark:text-gray-400">Pilih program untuk melihat raport kelas</p>
    </div>

    <!-- Program Stats - Minimalis -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $programs->count() }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Program</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $totalKelas }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Kelas</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ $totalKelas }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Raport Tersedia</p>
            </div>
        </div>
    </div>

    <!-- Program List - Minimalis -->
    <div class="space-y-4">
        @foreach($programs as $program)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Program Info -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                        {{ $program->title }}
                    </h3>

                    @if($program->description)
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                        {{ $program->description }}
                    </p>
                    @endif

                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center">
                            <i class="fas fa-chalkboard mr-2"></i>
                            {{ $program->kelas->count() }} kelas
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            {{ \Carbon\Carbon::parse($program->created_at)->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex-shrink-0">
                    <a href="{{ route('adminprogram.eraport.program', $program->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition">
                       <i class="fas fa-file-contract mr-2"></i>
                       Lihat Raport
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($programs->isEmpty())
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <i class="fas fa-cubes text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada program</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Tidak ada program yang dapat ditampilkan untuk E-Raport.</p>
        <a href="{{ route('adminprogram.kelas.create') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition">
           <i class="fas fa-plus mr-2"></i>
           Buat Program Baru
        </a>
    </div>
    @endif
</div>
@endsection
