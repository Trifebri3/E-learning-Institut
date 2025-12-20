@extends('adminprogram.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Edit Materi
        </h1>
        <a href="{{ route('adminprogram.resources.indexByProgram', $resource->kelas->program_id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 space-y-6">

            <form action="{{ route('adminprogram.resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Judul Materi <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title"
                               value="{{ old('title', $resource->title) }}"
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2"
                               placeholder="Contoh: Pengenalan Dasar HTML">
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
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2"
                                  placeholder="Deskripsi singkat tentang materi ini...">{{ old('description', $resource->description) }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Link URL (Video/Artikel)
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 sm:text-sm">
                                https://
                            </span>
                            <input type="url" name="link_url" id="link_url"
                                   value="{{ old('link_url', $resource->link_url) }}"
                                   class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2"
                                   placeholder="youtube.com/watch?v=...">
                        </div>
                        @error('link_url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            File Dokumen
                        </label>

                        @if($resource->file_path)
                            <div class="mt-2 flex items-center p-3 text-sm text-blue-800 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-300 rounded-md border border-blue-100 dark:border-blue-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="truncate flex-1">File saat ini: <strong>{{ basename($resource->file_path) }}</strong></span>
                                <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="font-medium underline hover:text-blue-600 ml-2">Lihat</a>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengubah file.</p>
                        @endif

                        <div class="mt-2">
                            <input type="file" name="file"
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          dark:file:bg-gray-600 dark:file:text-white">
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="is_published" name="is_published" type="checkbox" value="1"
                               {{ old('is_published', $resource->is_published) ? 'checked' : '' }}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-offset-gray-800">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_published" class="font-medium text-gray-700 dark:text-gray-300">Terbitkan Materi</label>
                        <p class="text-gray-500 dark:text-gray-400">Jika dicentang, materi akan langsung terlihat oleh peserta.</p>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <a href="{{ route('adminprogram.resources.indexByProgram', $resource->kelas->program_id) }}"
                       class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
