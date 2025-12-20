@extends('instructor.layouts.app')

@section('title', 'Ruang Instruktur')

@section('content')
<div class="container mx-auto p-6">

    <!-- Header -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ruang Instruktur</h1>
            <p class="text-gray-600 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}. Siap mengajar hari ini?</p>
        </div>
        <span class="text-sm bg-purple-100 text-purple-800 py-1 px-3 rounded-full font-bold dark:bg-purple-900 dark:text-purple-200">
            {{ now()->format('l, d F Y') }}
        </span>
    </div>

    <!-- 1. STATISTIC CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Program Diampu -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-purple-500 flex items-center justify-between transition hover:shadow-md">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Program Diampu</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_programs'] }}</h2>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full text-purple-600">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
        </div>

        <!-- Total Siswa -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-blue-500 flex items-center justify-between transition hover:shadow-md">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Total Siswa</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_students'] }}</h2>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full text-blue-600">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>

        <!-- Kelas Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-green-500 flex items-center justify-between transition hover:shadow-md">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Kelas / Sesi</p>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total_classes'] }}</h2>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full text-green-600">
                <i class="fas fa-book-open text-xl"></i>
            </div>
        </div>

        <!-- Perlu Dinilai (PRIORITAS) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border-l-4 border-yellow-500 flex items-center justify-between transition hover:shadow-md cursor-pointer" onclick="alert('Fitur pintasan ke halaman grading akan segera hadir!')">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Perlu Dinilai</p>
                <h2 class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['needs_grading'] }}</h2>
            </div>
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full text-yellow-600 relative">
                <i class="fas fa-marker text-xl"></i>
                @if($stats['needs_grading'] > 0)
                    <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- 2. TABEL PROGRAM SAYA (2/3 Lebar) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="font-bold text-gray-800 dark:text-white"><i class="fas fa-list-ul mr-2"></i> Program yang Anda Ajar</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Nama Program</th>
                            <th class="px-6 py-3">Lokasi</th>
                            <th class="px-6 py-3 text-center">Peserta</th>
                            <th class="px-6 py-3 text-center">Kelas</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($myPrograms as $program)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $program->title }}
                                <span class="block text-xs text-gray-500 font-mono mt-1">{{ $program->redeem_code }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $program->lokasi }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">{{ $program->participants_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-1 rounded-full">{{ $program->kelas_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{-- Tombol dummy, nanti bisa diarahkan ke halaman detail --}}
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold border border-indigo-200 px-3 py-1 rounded hover:bg-indigo-50">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Anda belum ditugaskan di program manapun.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. TUGAS MASUK TERBARU (1/3 Lebar) -->
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                <i class="fas fa-inbox mr-2 text-green-500"></i> Tugas Masuk Terbaru
            </h3>

            <div class="space-y-4">
                @forelse($recentSubmissions as $sub)
                    <div class="flex items-start space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($sub->user->name) }}&background=random" class="w-10 h-10 rounded-full shadow-sm">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $sub->user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">Mengumpulkan: {{ $sub->assignment->title }}</p>
                            <p class="text-[10px] text-green-600 mt-1 font-medium">
                                {{ \Carbon\Carbon::parse($sub->submitted_at)->diffForHumans() }}
                                @if($sub->is_late) <span class="text-red-500">(Terlambat)</span> @endif
                            </p>
                        </div>
                        {{-- Tombol Nilai (Dummy Link) --}}
                        <a href="#" class="text-xs bg-indigo-600 text-white px-2 py-1 rounded shadow hover:bg-indigo-700 self-center">Nilai</a>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-2 text-gray-300"></i>
                        <p class="text-sm">Semua aman! Belum ada tugas baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
