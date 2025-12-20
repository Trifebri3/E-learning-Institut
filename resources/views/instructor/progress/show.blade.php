@extends('instructor.layouts.app')

@section('title', 'Leger Nilai: ' . $kelas->title)

@section('content')
<div class="container mx-auto p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Leger Nilai: {{ $kelas->title }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Program: {{ $kelas->program->title }}</p>
        </div>
        <a href="{{ route('instructor.progress.index') }}" class="text-gray-500 hover:text-purple-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- INFO ADMINISTRATOR ACCESS -->
    <div class="mb-6 p-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-bold text-blue-800">Akses Terbatas</h3>
                <p class="text-blue-700 mt-2">
                    <strong>Fitur ini hanya dapat diakses oleh Administrator Program.</strong>
                </p>
                <p class="text-blue-600 mt-1">
                    Untuk melihat progress dan nilai peserta secara lengkap, silahkan hubungi Administrator Program Anda.
                </p>
                <div class="mt-4 p-4 bg-white rounded border border-blue-200">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <strong>Fitur yang tersedia untuk Instruktur:</strong>
                    </p>
                    <ul class="text-sm text-blue-700 mt-2 ml-6 list-disc">
                        <li>Melihat daftar kelas yang diajar</li>
                        <li>Mengelola materi pembelajaran</li>
                        <li>Memberikan feedback pada tugas</li>
                        <li>Memantau kehadiran peserta</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD INFORMASI KELAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg mr-4">
                    <i class="fas fa-users text-purple-600 dark:text-purple-400"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Peserta</p>
                    <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $participants->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg mr-4">
                    <i class="fas fa-calendar text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Kelas</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $kelas->tanggal }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg mr-4">
                    <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">
                        {{ $kelas->is_published ? 'Published' : 'Draft' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- PESERTA TERDAFTAR (Hanya Nama) -->
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden border dark:border-gray-700">
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-bold text-gray-700 dark:text-gray-200">Daftar Peserta Terdaftar</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Menampilkan {{ $participants->count() }} peserta - Detail nilai hanya dapat diakses oleh Administrator Program
            </p>
        </div>

        <div class="p-6">
            @if($participants->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($participants as $user)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 dark:text-purple-400 font-bold text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $user->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $user->nomorInduks->first()->nomor_induk ?? 'No NIM' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada peserta yang terdaftar di kelas ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- CONTACT ADMIN -->
    <div class="mt-6 p-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold">Butuh Akses Lebih?</h3>
                <p class="mt-1 opacity-90">Hubungi Administrator Program untuk akses lengkap progress peserta</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="alert('Fitur kontak admin akan segera tersedia')"
                        class="px-4 py-2 bg-white text-blue-600 rounded-lg font-bold hover:bg-blue-50 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>Email Admin
                </button>
                <button onclick="alert('Fitur kontak admin akan segera tersedia')"
                        class="px-4 py-2 bg-blue-700 text-white rounded-lg font-bold hover:bg-blue-800 transition-colors">
                    <i class="fas fa-phone mr-2"></i>Hubungi
                </button>
            </div>
        </div>
    </div>

</div>

<style>
.shadow-r {
    box-shadow: 4px 0 4px -2px rgba(0,0,0,0.1);
}
.shadow-l {
    box-shadow: -4px 0 4px -2px rgba(0,0,0,0.1);
}
</style>
@endsection
