@extends('participant.layouts.app')

@section('title', 'Buat Tiket Bantuan')

@section('content')
<div class="min-h-screen py-8" x-data="{ category: '{{ old('category', '') }}' }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
            <a href="{{ route('participant.support.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors mb-4">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Kembali
            </a>

            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Buat Tiket Baru
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                Sampaikan kendala atau pertanyaan Anda. Kami akan segera membantu.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-bold text-red-800 dark:text-red-200">Terjadi Kesalahan</h3>
                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 dark:text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('participant.support.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 md:p-8 space-y-6">

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Kategori Laporan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="category" name="category" x-model="category" required
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow appearance-none">
                                    <option value="" disabled selected>Pilih kategori masalah...</option>
                                    <option value="general">Laporan Umum (Etika/Saran)</option>
                                    <option value="academic">Akademik (Materi/Tugas)</option>
                                    <option value="permission">Perizinan (Sakit/Absen)</option>
                                    <option value="system">Teknis (Error/Akun)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div x-show="category == 'academic' || category == 'permission'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-700">
                            <label for="program_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Program Terkait <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="program_id" name="program_id"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow appearance-none">
                                    <option value="" disabled selected>Pilih program...</option>
                                    @foreach($myPrograms as $program)
                                        <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                            {{ $program->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                <span x-text="category == 'academic' ? 'Pilih program yang materi atau tugasnya bermasalah.' : 'Pilih program yang ingin Anda ajukan izin.'"></span>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="subject" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Judul / Subjek <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                   placeholder="Contoh: Tidak bisa upload tugas di modul 3"
                                   class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow placeholder-gray-400">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="6" required
                                      placeholder="Jelaskan masalah Anda sedetail mungkin..."
                                      class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow resize-y placeholder-gray-400">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div>
                            <label for="priority" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Tingkat Urgensi
                            </label>
                            <div class="relative">
                                <select id="priority" name="priority"
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow appearance-none">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low (Tanya Jawab)</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium (Kendala Belajar)</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High (Darurat/Sistem Down)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <i class="fas fa-sort text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="attachment" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Lampiran (Opsional)
                            </label>
                            <input type="file" id="attachment" name="attachment"
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-gray-100 file:text-gray-700
                                          hover:file:bg-gray-200
                                          dark:file:bg-gray-600 dark:file:text-gray-200
                                          cursor-pointer border border-gray-200 dark:border-gray-600 rounded-xl">
                            <p class="mt-1.5 text-xs text-gray-400">
                                Max: 2MB (JPG, PNG, PDF)
                            </p>
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center md:text-left">
                        <i class="fas fa-clock mr-1"></i> Respon rata-rata dalam 24 jam kerja.
                    </p>
                    <button type="submit"
                            class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Tiket
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
