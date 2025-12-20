@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Bab Materi</h1>
            <p class="text-gray-500 text-sm">Kurikulum: {{ $learningPath->title }}</p>
        </div>
        <a href="{{ route('adminprogram.learningpath.manage', $learningPath->id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('adminprogram.learningpath.section.store', $learningPath->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Baris 1 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Bab</label>
                    <input type="text" name="title" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white" required placeholder="Contoh: Pendahuluan Jaringan">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Urutan</label>
                    <input type="number" name="order" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white" value="{{ $learningPath->sections->count() + 1 }}" required>
                </div>
            </div>

            <!-- Konten -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Isi Materi</label>
                <textarea name="content" rows="12" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white font-mono text-sm" required placeholder="Tulis materi lengkap di sini... (Support HTML dasar)"></textarea>
            </div>

            <!-- Gambar -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Gambar Pendukung (Opsional)</label>
                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="text-right">
                <button type="submit" class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg shadow hover:bg-green-700 transition">
                    Simpan Materi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
