@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Konten Program</h1>
        <a href="{{ route('adminprogram.programs.index') }}" class="text-gray-500 hover:text-blue-600">Kembali</a>
    </div>

    <form action="{{ route('adminprogram.programs.update', $program->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- KOLOM KIRI -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-4 text-indigo-600">Detail Program</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Nama Program</label>
                        <input type="text" name="title" value="{{ old('title', $program->title) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Kuota</label>
                            <input type="number" name="kuota" value="{{ old('kuota', $program->kuota) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $program->lokasi) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Mulai</label>
                            <input type="date" name="waktu_mulai" value="{{ old('waktu_mulai', \Carbon\Carbon::parse($program->waktu_mulai)->format('Y-m-d')) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Selesai</label>
                            <input type="date" name="waktu_selesai" value="{{ old('waktu_selesai', \Carbon\Carbon::parse($program->waktu_selesai)->format('Y-m-d')) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-4 text-indigo-600">Deskripsi & Konten</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Deskripsi Singkat</label>
                        <textarea name="deskripsi_singkat" rows="2" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ old('deskripsi_singkat', $program->deskripsi_singkat) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Deskripsi Lengkap</label>
                        <textarea name="deskripsi_lengkap" rows="6" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ old('deskripsi_lengkap', $program->deskripsi_lengkap) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-4 text-indigo-600">Visual</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Logo</label>
                        @if($program->logo_path)
                            <img src="{{ Storage::url($program->logo_path) }}" class="h-20 w-20 object-cover rounded mb-2 border">
                        @endif
                        <input type="file" name="logo" class="block w-full text-sm text-gray-500 mt-1">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Banner</label>
                        @if($program->banner_path)
                            <img src="{{ Storage::url($program->banner_path) }}" class="h-24 w-full object-cover rounded mb-2 border">
                        @endif
                        <input type="file" name="banner" class="block w-full text-sm text-gray-500 mt-1">
                    </div>
                </div>

                <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
                    <h4 class="font-bold text-blue-800 dark:text-blue-300 mb-2">Kelola Modul Lain?</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">Untuk mengelola Kelas, Narasumber, atau Materi, silakan akses menu terkait di sidebar.</p>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-lg transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
