@extends('participant.layouts.app')

@section('title', 'Transkrip Nilai: ' . $kelas->title)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('participant.progress.index') }}"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors mb-4">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali ke Daftar Kelas
            </a>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                        {{ $kelas->title }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                        <i class="fas fa-layer-group text-xs"></i>
                        Program: {{ $kelas->program->title ?? 'Program Umum' }}
                    </p>
                </div>

                <button onclick="window.print()" class="hidden md:inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-print mr-2"></i> Cetak Transkrip
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8 text-center sticky top-24">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6">Nilai Akhir</h3>

                    <div class="relative w-40 h-40 mx-auto mb-6">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45"
                                    class="stroke-gray-100 dark:stroke-gray-700 fill-none stroke-[8]" />
                            <circle cx="50" cy="50" r="45"
                                    class="fill-none stroke-[8] transition-all duration-1000 ease-out {{ $finalGrade >= 60 ? 'stroke-green-500' : 'stroke-red-500' }}"
                                    stroke-dasharray="283"
                                    stroke-dashoffset="{{ 283 - (283 * $finalGrade / 100) }}"
                                    stroke-linecap="round" />
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-extrabold {{ $finalGrade >= 60 ? 'text-gray-900 dark:text-white' : 'text-red-500' }}">
                                {{ number_format($finalGrade, 1) }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium uppercase mt-1">Skor Total</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        @if($finalGrade >= 80)
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-100 dark:border-green-800">
                                <i class="fas fa-star mr-2"></i> Sangat Memuaskan
                            </span>
                        @elseif($finalGrade >= 60)
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                <i class="fas fa-check-circle mr-2"></i> Lulus
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-100 dark:border-red-800">
                                <i class="fas fa-exclamation-circle mr-2"></i> Belum Lulus
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Nilai dikalkulasikan secara otomatis dari bobot tugas, kuis, dan ujian essay yang telah diselesaikan.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-list-ul text-primary-500"></i> Rincian Komponen
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($components as $name => $data)
                        <div class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:border-primary-300 dark:hover:border-primary-700 transition-all duration-200 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-800 dark:text-white text-base">
                                        {{ $name }}
                                    </h4>
                                    @if($data['exists'] && isset($data['details']))
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                            {{ $data['details'] }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="text-xl font-bold {{ $data['exists'] ? ($data['score'] >= 60 ? 'text-gray-900 dark:text-white' : 'text-red-500') : 'text-gray-300 dark:text-gray-600' }}">
                                        {{ $data['exists'] ? $data['score'] : '-' }}<span class="text-sm font-normal text-gray-400">%</span>
                                    </span>
                                </div>
                            </div>

                            @if($data['exists'])
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 mb-3 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-1000 ease-out {{ $data['score'] >= 60 ? 'bg-primary-600' : 'bg-red-500' }}"
                                         style="width: 0%"
                                         data-width="{{ $data['score'] }}%"></div>
                                </div>

                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400 dark:text-gray-500">Status</span>
                                    @if($data['score'] >= 80)
                                        <span class="font-bold text-green-600 dark:text-green-400">Sangat Baik</span>
                                    @elseif($data['score'] >= 60)
                                        <span class="font-bold text-blue-600 dark:text-blue-400">Baik</span>
                                    @else
                                        <span class="font-bold text-red-500">Perlu Remedial</span>
                                    @endif
                                </div>
                            @else
                                <div class="py-2 flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500 italic bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3">
                                    <i class="fas fa-ban"></i> Komponen ini tidak tersedia di kelas ini.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/10 rounded-xl p-4 border border-blue-100 dark:border-blue-800/50 flex gap-3 items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <div class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                        <strong>Catatan:</strong> Standar kelulusan minimal adalah 60%. Pastikan seluruh tugas dan ujian telah dikerjakan untuk mendapatkan nilai maksimal.
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate Progress Bars
        const progressBars = document.querySelectorAll('[data-width]');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const bar = entry.target;
                    // Add small delay for visual effect
                    setTimeout(() => {
                        bar.style.width = bar.dataset.width;
                    }, 100);
                    observer.unobserve(bar);
                }
            });
        }, { threshold: 0.1 });

        progressBars.forEach(bar => observer.observe(bar));
    });
</script>
@endpush
