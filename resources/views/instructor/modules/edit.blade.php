@extends('instructor.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Modul</h1>
            <p class="text-gray-500 text-sm">Kelas: {{ $module->kelas->title }}</p>
        </div>
        <a href="{{ route('instructor.kelas.edit', $module->kelas_id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('instructor.modules.update', $module->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Modul</label>
                <input type="text" name="title" value="{{ old('title', $module->title) }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" required>
            </div>

            <!-- Konten -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Isi Materi</label>
                <textarea name="content" rows="10" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" required>{{ old('content', $module->content) }}</textarea>
            </div>

            <!-- Pengaturan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Urutan Tampil</label>
                    <input type="number" name="order" value="{{ old('order', $module->order) }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white" required>
                </div>

            </div>

            <div class="flex justify-between items-center pt-4 border-t dark:border-gray-700">
                <!-- Tombol Hapus (di kiri) -->
                <button type="button" onclick="document.getElementById('delete-form').submit();" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                    <i class="fas fa-trash mr-1"></i> Hapus Modul
                </button>

                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Form Delete Tersembunyi -->
        <form id="delete-form" action="{{ route('instructor.modules.destroy', $module->id) }}" method="POST" style="display: none;" onsubmit="return confirm('Yakin ingin menghapus modul ini?');">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
