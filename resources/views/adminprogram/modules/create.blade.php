@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Modul Bacaan</h1>
            <p class="text-gray-500 text-sm">Kelas: {{ $kelas->title }}</p>
        </div>
        <a href="{{ route('adminprogram.kelas.edit', $kelas->id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('adminprogram.modules.store', $kelas->id) }}" method="POST">
            @csrf

            <!-- Judul -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Modul</label>
                <input type="text" name="title" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" placeholder="Contoh: Pengantar Internet of Things" required>
            </div>

            <!-- Konten (Textarea) -->
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Isi Materi</label>
                <textarea name="content" rows="10" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500" placeholder="Tulis materi pembelajaran di sini... (Support HTML sederhana)" required></textarea>
                <p class="text-xs text-gray-500 mt-1">Tips: Gunakan &lt;b&gt; untuk tebal, &lt;br&gt; untuk baris baru.</p>
            </div>

            <!-- Pengaturan Tambahan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Urutan Tampil</label>
                    <input type="number" name="order" value="1" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_mandatory" class="w-5 h-5 text-green-600 rounded focus:ring-green-500" checked>
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-gray-700 dark:text-gray-300">Wajib Dibaca?</span>
                            <span class="block text-xs text-gray-500">Jika dicentang, peserta harus membuka modul ini agar tercatat selesai.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition">
                    <i class="fas fa-save mr-2"></i> Simpan Modul
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
