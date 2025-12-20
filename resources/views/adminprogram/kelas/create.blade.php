@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Jadwalkan Kelas Baru</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Buat jadwal kelas untuk program pelatihan</p>
        </div>
        <a href="{{ route('adminprogram.kelas.index') }}"
           class="flex items-center text-gray-500 hover:text-indigo-600 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('adminprogram.kelas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 space-y-8">

            <!-- Section: Program & Tipe Kelas -->
            <div class="border-b dark:border-gray-700 pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-cube mr-2 text-indigo-600"></i>
                    Informasi Program
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Program Selection -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            Program
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="program_id" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Pilih Program --</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id }}">{{ $prog->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipe Kelas -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            Tipe Kelas
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="tipe" id="tipeKelas" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="toggleZoomInput()" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="materi">📚 Materi Mandiri (Asynchronous)</option>
                            <option value="interaktif">🎥 Interaktif / Live (Synchronous)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section: Detail Kelas -->
            <div class="border-b dark:border-gray-700 pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                    Detail Kelas
                </h2>

                <!-- Judul Kelas -->
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                        Judul Kelas
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="title" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Contoh: Sesi 1 - Pengenalan IoT dan Implementasinya" required>
                </div>

                <!-- Link Zoom (Conditional) -->
                <div id="zoomInput" class="hidden transition-all duration-300">
                    <label class="block text-sm font-bold mb-3 text-blue-600 dark:text-blue-400">
                        <i class="fas fa-video mr-1"></i>
                        Link Meeting / Zoom
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="url" name="link_zoom" class="w-full rounded-lg border-blue-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="https://zoom.us/j/xxxxxxxxx">
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Wajib diisi untuk kelas Interaktif / Live
                    </p>
                </div>
            </div>

            <!-- Section: Jadwal -->
            <div class="border-b dark:border-gray-700 pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>
                    Jadwal & Lokasi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Tanggal -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            Tanggal
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="date" name="tanggal" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <!-- Jam Mulai -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            Jam Mulai
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="time" name="jam_mulai" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <!-- Jam Selesai -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            Jam Selesai
                        </label>
                        <input type="time" name="jam_selesai" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="mt-4">
                    <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                        Lokasi / Platform
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="tempat" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           value="LMS E-Learning" required>
                </div>
            </div>

            <!-- Section: Konten -->
            <div class="border-b dark:border-gray-700 pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-indigo-600"></i>
                    Konten Kelas
                </h2>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                        Deskripsi Materi
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea name="deskripsi" rows="5" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Jelaskan materi yang akan dibahas dalam kelas ini..." required></textarea>
                </div>

                <!-- Banner -->
                <div>
                    <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                        <i class="fas fa-image mr-1"></i>
                        Banner Kelas (Opsional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-indigo-400 transition">
                        <input type="file" name="banner" class="hidden" id="bannerInput" onchange="previewBanner(event)">
                        <label for="bannerInput" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400">Klik untuk upload banner</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                        </label>
                    </div>
                    <div id="bannerPreview" class="mt-3 hidden">
                        <img id="preview" class="max-w-xs rounded-lg shadow">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 text-right">
                <button type="submit"
                        class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Buat Kelas & Lanjut Setup
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleZoomInput() {
        const type = document.getElementById('tipeKelas').value;
        const zoomDiv = document.getElementById('zoomInput');
        const zoomInput = document.querySelector('input[name="link_zoom"]');

        if (type === 'interaktif') {
            zoomDiv.classList.remove('hidden');
            zoomInput.required = true;
        } else {
            zoomDiv.classList.add('hidden');
            zoomInput.required = false;
            zoomInput.value = '';
        }
    }

    function previewBanner(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('bannerPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="tanggal"]').min = today;
    });
</script>
@endsection
