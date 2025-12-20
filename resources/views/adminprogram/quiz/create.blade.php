@extends('adminprogram.layouts.app')

@section('title', 'Buat Quiz Baru')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Quiz Baru</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Buat quiz untuk menilai pemahaman peserta</p>
        </div>
        <a href="{{ route('adminprogram.quiz.index') }}"
           class="flex items-center text-gray-500 hover:text-indigo-600 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('adminprogram.quiz.store') }}" method="POST">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 space-y-8">

            <!-- Section: Informasi Dasar -->
            <div class="border-b dark:border-gray-700 pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                    Informasi Dasar Quiz
                </h2>

                <!-- Judul Quiz -->
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                        Judul Quiz
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3"
                           placeholder="Contoh: Quiz Sesi 1 - Pengenalan IoT" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Program / Kelas -->
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                        Program / Kelas
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="kelas_id"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->title }} - {{ $kelas->program->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Section: Pengaturan Quiz -->
            <div class="border-b dark:border-gray-700 pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-cog mr-2 text-indigo-600"></i>
                    Pengaturan Quiz
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Durasi -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            <i class="fas fa-clock mr-1 text-blue-500"></i>
                            Durasi (menit)
                        </label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}"
                               class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3"
                               min="1" max="480">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Default: 60 menit
                        </p>
                    </div>

                    <!-- Maksimal Attempt -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            <i class="fas fa-redo mr-1 text-green-500"></i>
                            Maksimal Attempt
                        </label>
                        <input type="number" name="max_attempts" value="{{ old('max_attempts') }}"
                               class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3"
                               min="0" max="10">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            0 = tanpa batas attempt
                        </p>
                    </div>

                    <!-- Publikasi -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            <i class="fas fa-eye mr-1 text-purple-500"></i>
                            Status Publikasi
                        </label>
                        <select name="is_published"
                                class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500 px-4 py-3">
                            <option value="1" {{ old('is_published', 1) == 1 ? 'selected' : '' }} class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Published
                            </option>
                            <option value="0" {{ old('is_published', 1) == 0 ? 'selected' : '' }} class="flex items-center">
                                <i class="fas fa-clock mr-2 text-yellow-500"></i>
                                Draft
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Quiz dapat diakses peserta jika published
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section: Informasi Tambahan -->
            <div class="pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-lightbulb mr-2 text-indigo-600"></i>
                    Tips & Informasi
                </h2>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div class="text-sm text-blue-800 dark:text-blue-300">
                            <p class="font-semibold mb-2">Setelah membuat quiz, Anda dapat:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Menambahkan soal pilihan ganda atau essay</li>
                                <li>Mengatur bobot nilai untuk setiap soal</li>
                                <li>Melihat hasil submission peserta</li>
                                <li>Mendownload hasil quiz dalam format PDF</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                <a href="{{ route('adminprogram.quiz.index') }}"
                   class="flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition shadow">
                   <i class="fas fa-times mr-2"></i>
                   Batal
                </a>
                <button type="submit"
                        class="flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition shadow-lg hover:shadow-xl">
                   <i class="fas fa-save mr-2"></i>
                   Simpan Quiz & Lanjut ke Soal
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Auto-formatting untuk input number
    document.addEventListener('DOMContentLoaded', function() {
        const durationInput = document.querySelector('input[name="duration_minutes"]');
        const attemptsInput = document.querySelector('input[name="max_attempts"]');

        // Set default values jika kosong
        if (!durationInput.value) durationInput.value = 60;
        if (!attemptsInput.value) attemptsInput.value = 0;

        // Validasi real-time
        durationInput.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
            if (this.value > 480) this.value = 480;
        });

        attemptsInput.addEventListener('change', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 10) this.value = 10;
        });
    });
</script>
@endsection
