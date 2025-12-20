@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Video Pembelajaran</h1>
            <p class="text-gray-500 text-sm">Kelas: {{ $kelas->title }}</p>
        </div>
        <a href="{{ route('adminprogram.kelas.edit', $kelas->id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('adminprogram.videos.store', $kelas->id) }}" method="POST">
            @csrf

            <!-- Judul -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Video</label>
                <input type="text" name="title" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Tutorial Dasar IoT Bagian 1" required>
            </div>

            <!-- URL Youtube -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Link YouTube / Video ID</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300">
                        <i class="fab fa-youtube text-lg text-red-600"></i>
                    </span>
                    <input type="text" name="youtube_url" class="w-full rounded-r-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500" placeholder="https://www.youtube.com/watch?v=..." required>
                </div>
                <p class="text-xs text-gray-500 mt-1">Cukup copy-paste URL lengkap video dari browser.</p>
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Keterangan / Deskripsi</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan poin penting video ini..."></textarea>
            </div>

            <!-- Pengaturan -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" class="w-5 h-5 text-green-600 rounded focus:ring-green-500" checked>
                    <div class="ml-3">
                        <span class="block text-sm font-bold text-gray-700 dark:text-gray-300">Publikasikan Sekarang?</span>
                        <span class="block text-xs text-gray-500">Jika tidak dicentang, video tidak akan muncul di halaman peserta.</span>
                    </div>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow transition">
                    <i class="fas fa-save mr-2"></i> Simpan Video
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
