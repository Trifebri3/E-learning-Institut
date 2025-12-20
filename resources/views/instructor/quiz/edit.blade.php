@extends('instructor.layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Quiz</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Update pengaturan quiz "{{ $quiz->title }}"</p>
        </div>
        <a href="{{ route('instructor.quiz.index') }}"
           class="flex items-center text-gray-500 hover:text-indigo-600 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>

    <form action="{{ route('instructor.quiz.update', $quiz->id) }}" method="POST">
        @csrf
        @method('PUT')

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
                    <input type="text" name="title" value="{{ old('title', $quiz->title) }}"
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
                            <option value="{{ $kelas->id }}" {{ old('kelas_id', $quiz->kelas_id) == $kelas->id ? 'selected' : '' }}>
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
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $quiz->duration_minutes) }}"
                               class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3"
                               min="1" max="480" required>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Waktu pengerjaan quiz
                        </p>
                    </div>

                    <!-- Maksimal Attempt -->
                    <div>
                        <label class="block text-sm font-bold mb-3 text-gray-700 dark:text-gray-300">
                            <i class="fas fa-redo mr-1 text-green-500"></i>
                            Maksimal Attempt
                        </label>
                        <input type="number" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}"
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
                            <option value="1" {{ old('is_published', $quiz->is_published) == 1 ? 'selected' : '' }}>
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Published
                            </option>
                            <option value="0" {{ old('is_published', $quiz->is_published) == 0 ? 'selected' : '' }}>
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

            <!-- Section: Statistik Quiz -->
            <div class="pb-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                    Statistik Quiz
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <i class="fas fa-question-circle text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-3">

                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <i class="fas fa-list-check text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Submission</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $quiz->attempts->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <i class="fas fa-users text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Peserta Aktif</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $quiz->kelas->participants->count() ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-yellow-500 mt-1 mr-3"></i>
                    <div class="flex-1">
                        <p class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">Aksi Cepat:</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('instructor.quiz.questions.index', $quiz->id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                               <i class="fas fa-question-circle mr-1"></i>
                               Kelola Soal
                            </a>
                            <a href="{{ route('instructor.quiz.submissions', $quiz->id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition">
                               <i class="fas fa-list-check mr-1"></i>
                               Lihat Submission
                            </a>
<a href="{{ route('instructor.quiz.download', $quiz->id) }}"
   class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
   <i class="fas fa-download mr-1"></i>
   Download PDF
</a>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t dark:border-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Terakhir update: {{ $quiz->updated_at->format('d M Y H:i') }}
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('instructor.quiz.index') }}"
                       class="flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition shadow">
                       <i class="fas fa-times mr-2"></i>
                       Batal
                    </a>
                    <button type="submit"
                            class="flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition shadow-lg hover:shadow-xl">
                       <i class="fas fa-save mr-2"></i>
                       Update Quiz
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const durationInput = document.querySelector('input[name="duration_minutes"]');
        const attemptsInput = document.querySelector('input[name="max_attempts"]');

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
