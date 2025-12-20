@extends('adminprogram.layouts.app')

@section('title', 'Dashboard Admin Program')

@section('content')
<div class="container mx-auto p-6">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard Program</h1>
        <p class="text-gray-600 dark:text-gray-400">Ringkasan aktivitas program yang Anda kelola.</p>
    </div>

    <!-- 1. STATISTIC CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Card: Program Saya -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-indigo-500 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Program Dikelola</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_programs'] }}</h2>
            </div>
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-full text-indigo-600">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
        </div>

        <!-- Card: Total Peserta -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Total Peserta</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_participants'] }}</h2>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full text-blue-600">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>

        <!-- Card: Kelas Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Kelas Aktif</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['active_classes'] }}</h2>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full text-green-600">
                <i class="fas fa-chalkboard text-xl"></i>
            </div>
        </div>

        <!-- Card: Perlu Dinilai (URGENT) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-yellow-500 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Menunggu Nilai</p>
                <h2 class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_grading'] }}</h2>
            </div>
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full text-yellow-600">
                <i class="fas fa-clipboard-check text-xl animate-pulse"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- 2. GRAFIK PESERTA (KOLOM KIRI - 2/3) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">
                <i class="fas fa-chart-bar text-indigo-500 mr-2"></i> Peserta per Program (Top 5)
            </h3>

            @if(empty($chartData))
                <p class="text-center text-gray-500 py-10">Belum ada data peserta.</p>
            @else
                <div class="space-y-4">
                    @foreach($chartLabels as $index => $label)
                        @php
                            $count = $chartData[$index];
                            $max = max($chartData) > 0 ? max($chartData) : 1;
                            $percent = ($count / $max) * 100;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                <span class="font-bold text-indigo-600">{{ $count }} Peserta</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 3. AKTIVITAS & SUPPORT (KOLOM KANAN - 1/3) -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Tiket Bantuan (Priority) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-red-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-red-600 dark:text-red-400"><i class="fas fa-life-ring mr-2"></i> Keluhan Baru</h3>
                    <a href="{{ route('adminprogram.support.index') }}" class="text-xs text-blue-500 hover:underline">Lihat Semua</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentTickets as $ticket)
                        <a href="{{ route('adminprogram.support.show', $ticket->id) }}" class="block p-3 bg-red-50 dark:bg-red-900/10 rounded-lg hover:bg-red-100 transition border border-red-100 dark:border-red-800/30">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-200">{{ Str::limit($ticket->user->name, 15) }}</span>
                                <span class="text-[10px] text-gray-400">{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm font-medium text-red-800 dark:text-red-300 mt-1 truncate">{{ $ticket->subject }}</p>
                        </a>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-2">Tidak ada tiket baru (Open).</p>
                    @endforelse
                </div>
            </div>

            <!-- Tugas Masuk -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-white mb-4"><i class="fas fa-file-import mr-2 text-green-500"></i> Tugas Masuk Terbaru</h3>
                <div class="space-y-4">
                    @forelse($recentSubmissions as $sub)
                        <div class="flex items-start space-x-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($sub->user->name) }}&background=random" class="w-8 h-8 rounded-full">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $sub->user->name }}</p>
                                <p class="text-xs text-gray-500 truncate">Mengumpulkan: {{ $sub->assignment->title }}</p>
<p class="text-[10px] text-green-600">
    {{ $sub->submitted_at ? \Carbon\Carbon::parse($sub->submitted_at)->diffForHumans() : 'Belum submit' }}
</p>

                            </div>
                            <a href="{{ route('adminprogram.assignments.index') }}" class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded hover:bg-gray-200">Nilai</a>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 text-center">Belum ada tugas masuk.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
