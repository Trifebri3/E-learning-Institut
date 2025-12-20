@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Kurikulum</h1>
            <p class="text-gray-500 text-sm">Kelas: {{ $learningPath->kelas->title }}</p>
        </div>
        <a href="{{ route('adminprogram.kelas.edit', $learningPath->kelas_id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelas
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kiri: Info & Edit Judul -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border dark:border-gray-700 sticky top-6">
                <h3 class="font-bold text-gray-800 dark:text-white mb-4 border-b pb-2">Pengaturan Utama</h3>

                <form action="{{ route('adminprogram.learningpath.update', $learningPath->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Judul Kurikulum</label>
                        <input type="text" name="title" value="{{ $learningPath->title }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                    </div>
                    <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700">Update Judul</button>
                </form>

                <div class="mt-8 pt-4 border-t dark:border-gray-600">
                    <form action="{{ route('adminprogram.learningpath.destroy', $learningPath->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kurikulum ini? Semua bab dan progress peserta akan hilang!');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-red-100 text-red-600 rounded font-bold hover:bg-red-200">Hapus Kurikulum</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kanan: Daftar Section -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white">Daftar Bab / Bagian</h3>
                    <a href="{{ route('adminprogram.learningpath.section.create', $learningPath->id) }}" class="px-3 py-1.5 bg-green-600 text-white text-sm font-bold rounded hover:bg-green-700">
                        <i class="fas fa-plus mr-1"></i> Tambah Bab
                    </a>
                </div>

                <div class="p-4 space-y-3">
                    @forelse($learningPath->sections as $section)
                        <div class="flex items-center justify-between p-4 border dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 font-bold rounded-full flex items-center justify-center text-sm">
                                    {{ $section->order }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 dark:text-white">{{ $section->title }}</h4>
                                    <p class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit(strip_tags($section->content), 60) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('adminprogram.learningpath.section.edit', $section->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('adminprogram.learningpath.section.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Hapus bab ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-book-open text-3xl mb-2 text-gray-300"></i>
                            <p>Belum ada materi. Silakan tambah bab baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
