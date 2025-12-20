@extends('superadmin.layouts.app')

@section('title', 'Buat Pengumuman Baru')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Pengumuman Baru</h1>
        <a href="{{ route('superadmin.announcements.index') }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('superadmin.announcements.store') }}" method="POST" enctype="multipart/form-data" x-data="{ type: 'global' }">
            @csrf

            <!-- Judul -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Judul Pengumuman <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Maintenance Server / Jadwal Libur">
            </div>

            <!-- Target Audience -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target Penerima <span class="text-red-500">*</span></label>
                    <select name="type" x-model="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500">
                        <option value="global">Global (Semua User)</option>
                        <option value="program">Spesifik Program</option>
                    </select>
                </div>

                <!-- Dropdown Program (Hanya muncul jika type = program) -->
                <div x-show="type === 'program'" x-transition>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pilih Program <span class="text-red-500">*</span></label>
                    <select name="program_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500">
                        <option value="" disabled selected>-- Pilih Program --</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Konten -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                <textarea name="content" rows="6" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500" placeholder="Tulis detail informasi di sini..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Prioritas -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tingkat Urgensi</label>
                    <select name="priority" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="normal">Normal (Biru)</option>
                        <option value="important">Penting (Kuning)</option>
                        <option value="critical">Kritis / Darurat (Merah)</option>
                    </select>
                </div>

                <!-- Lampiran -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Lampiran (Opsional)</label>
                    <input type="file" name="attachment" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                    <p class="text-xs text-gray-500 mt-1">PDF, Word, atau Gambar (Max 2MB)</p>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t dark:border-gray-700">
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-1">
                    <i class="fas fa-paper-plane mr-2"></i> Terbitkan Sekarang
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
