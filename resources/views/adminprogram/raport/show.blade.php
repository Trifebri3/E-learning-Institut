@extends('adminprogram.layouts.app')

@section('title', 'Rekap Raport - ' . $kelas->title)

@section('content')
<div class="container mx-auto p-6 max-w-6xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Rekap Raport Kelas</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ $kelas->title }} - {{ $kelas->program->title ?? 'Tidak ada program' }}
                </p>
            </div>
            <a href="{{ route('adminprogram.eraport.index') }}"
               class="flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition">
               <i class="fas fa-arrow-left mr-2"></i>
               Kembali
            </a>
        </div>
    </div>

    <!-- Class Stats - Minimalis -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ count($reportData) }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Peserta</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                    @php
$avgGrade = collect($reportData)
    ->map(fn ($item) => (float) $item['finalGrade'])
    ->avg();
@endphp
                    {{ number_format($avgGrade, 1) }}
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                    {{ collect($reportData)->where('finalGrade', '>=', 75)->count() }}
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Lulus</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                    {{ collect($reportData)->where('finalGrade', '<', 75)->count() }}
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Perlu Perbaikan</p>
            </div>
        </div>
    </div>

    <!-- Participant Reports -->
    <div class="space-y-6">
        @foreach($reportData as $data)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <!-- Participant Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-600">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">{{ $data['user']->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $data['user']->email }}</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-800 dark:text-white mb-1">
                        {{ $data['finalGrade'] }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $data['finalGrade'] >= 75 ? 'Lulus' : 'Perlu Perbaikan' }}
                    </p>
                </div>
            </div>

            <!-- Components Grid -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Komponen Nilai</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($data['components'] as $componentName => $items)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-800 dark:text-white text-sm">
                                {{ $componentName }}
                            </span>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $items }}%
                            </span>
                        </div>

                        @if($componentName == 'Presensi')
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gray-600 dark:bg-gray-400 h-2 rounded-full" style="width: {{ $items }}%"></div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Final Grade Summary -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Status Kelulusan</p>
                        <p class="font-semibold text-gray-800 dark:text-white">
                            {{ $data['finalGrade'] >= 75 ? 'LULUS' : 'TIDAK LULUS' }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-600 dark:text-gray-400">Keterangan</p>
                        <p class="font-semibold text-gray-800 dark:text-white">
                            @if($data['finalGrade'] >= 85) Sangat Memuaskan
                            @elseif($data['finalGrade'] >= 75) Memuaskan
                            @elseif($data['finalGrade'] >= 65) Cukup
                            @else Perlu Perbaikan
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if(empty($reportData))
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <i class="fas fa-file-invoice text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada data raport</h3>
        <p class="text-gray-500 dark:text-gray-400">Tidak ada data peserta atau nilai yang tersedia untuk kelas ini.</p>
    </div>
    @endif

    <!-- Action Button -->
    <div class="mt-8 flex justify-center">
        <a href="{{ route('adminprogram.raport.index') }}"
           class="flex items-center px-6 py-3 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition">
           <i class="fas fa-arrow-left mr-2"></i>
           Kembali ke Daftar Kelas
        </a>
    </div>
</div>
@endsection
