@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Video Pembelajaran</h1>
            <p class="text-gray-500 text-sm">Kelas: {{ $kelas->title }}</p>
        </div>
        <a href="{{ route('instructor.kelas.edit', $kelas->id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('instructor.videos.store', $kelas->id) }}" method="POST">
            @csrf

            <!-- Judul Video -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Video</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" placeholder="Contoh: Pengantar IoT" required>
                @error('title')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- URL YouTube -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">URL YouTube</label>
                <input type="text" name="youtube_url" value="{{ old('youtube_url') }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" placeholder="https://www.youtube.com/watch?v=ID_VIDEO" required>
                @error('youtube_url')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Deskripsi (opsional)</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" placeholder="Deskripsi video...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition">
                    <i class="fas fa-save mr-2"></i> Simpan Video
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
