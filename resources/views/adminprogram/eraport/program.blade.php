@extends('adminprogram.layouts.app')

@section('title', 'E-Raport - ' . $program->title)

@section('content')
<div class="container mx-auto p-6 max-w-7xl">
    <!-- Header dengan Breadcrumb -->
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('adminprogram.eraport.index') }}"
                       class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                       <i class="fas fa-arrow-left mr-2"></i>
                       Kembali ke Program
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $program->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $program->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Pilih kelas untuk melihat dan mengelola raport</p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $kelas->count() }} kelas tersedia
            </div>
        </div>
    </div>

    <!-- Kelas List - Minimalis -->
    <div class="space-y-4">
        @foreach($kelas as $k)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Kelas Info -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">
                        {{ $k->title }}
                    </h3>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">


                        @if($k->tanggal)
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2 sm:justify-end">
                    <a href="{{ route('adminprogram.raport.show', [$program->id, $k->id]) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition text-sm">
                       <i class="fas fa-file-contract mr-2"></i>
                       Lihat Raport
                    </a>


                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($kelas->isEmpty())
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <i class="fas fa-chalkboard-teacher text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada kelas</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Program ini belum memiliki kelas yang dapat ditampilkan.</p>
        <a href="{{ route('adminprogram.kelas.create') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition">
           <i class="fas fa-plus mr-2"></i>
           Buat Kelas Baru
        </a>
    </div>
    @endif
</div>
@endsection
