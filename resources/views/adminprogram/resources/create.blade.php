@extends('adminprogram.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Materi Baru
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Kelas: <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $kelas->title ?? $kelas->nama }}</span>
            </p>
        </div>
        <a href="{{ route('adminprogram.resources.indexByProgram', $kelas->program_id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6">

            <form action="{{ route('adminprogram.resources.store', $kelas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Judul Materi <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2"
                               placeholder="Contoh: Modul 1 - Pengenalan Sistem">
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Deskripsi
                    </label>
                    <div class="mt-1">
                        <textarea name="description" id="description" rows="3"
                                  class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2"
                                  placeholder="Deskripsi singkat tentang materi ini...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-2 bg-white dark:bg-gray-800 text-sm text-gray-500 dark:text-gray-400">
                            Isi Sumber Materi (Pilih Salah Satu atau Keduanya)
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Link URL (Video/Artikel)
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 sm:text-sm">
                                🔗
                            </span>
                            <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}"
                                   class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2"
                                   placeholder="https://youtube.com/...">
                        </div>
                        @error('link_url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Upload File Dokumen
                        </label>
                        <div class="mt-1">
                            <input type="file" name="file"
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          dark:file:bg-gray-600 dark:file:text-white
                                          border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Format: PDF, DOCX, PPTX, XLS, JPG, PNG (Max 20MB).
                        </p>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-start pt-2">
                    <div class="flex items-center h-5">
                        <input id="is_published" name="is_published" type="checkbox" value="1" {{ old('is_published') ? 'checked' : '' }}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_published" class="font-medium text-gray-700 dark:text-gray-300">Terbitkan Langsung</label>
                        <p class="text-gray-500 dark:text-gray-400">Materi akan langsung terlihat oleh peserta jika dicentang.</p>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <a href="{{ route('adminprogram.resources.indexByProgram', $kelas->program_id) }}"
                       class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Simpan Materi
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
