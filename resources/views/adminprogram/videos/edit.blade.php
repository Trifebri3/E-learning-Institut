@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Video</h1>
            <p class="text-gray-500 text-sm">Kelas: {{ $video->kelas->title }}</p>
        </div>
        <a href="{{ route('adminprogram.kelas.edit', $video->kelas_id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: FORM -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700">
            <form action="{{ route('adminprogram.videos.update', $video->id) }}" method="POST" id="edit-video-form">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Judul Video</label>
                    <input type="text" name="title" value="{{ old('title', $video->title) }}" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Link YouTube / ID</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300">
                            <i class="fab fa-youtube text-lg text-red-600"></i>
                        </span>
                        <input type="text" name="youtube_url" value="{{ old('youtube_url', 'https://youtu.be/' . $video->youtube_id) }}" class="w-full rounded-r-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Keterangan</label>
                    <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">{{ old('description', $video->description) }}</textarea>
                </div>

                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" class="w-5 h-5 text-green-600 rounded focus:ring-green-500" @checked($video->is_published)>
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-gray-700 dark:text-gray-300">Status Publikasi</span>
                        </div>
                    </label>
                </div>

                <div class="flex justify-between items-center pt-4 border-t dark:border-gray-700">
                    <button type="button" onclick="document.getElementById('delete-form').submit();" class="text-red-600 hover:text-red-800 font-bold text-sm">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <form id="delete-form" action="{{ route('adminprogram.videos.destroy', $video->id) }}" method="POST" style="display: none;" onsubmit="return confirm('Yakin ingin menghapus video ini? Data tonton peserta juga akan hilang.');">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <!-- KOLOM KANAN: PREVIEW -->
        <div class="lg:col-span-1">
            <div class="bg-black rounded-xl overflow-hidden shadow-lg sticky top-6">
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="https://www.youtube.com/embed/{{ $video->youtube_id }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                </div>
                <div class="p-4 bg-gray-800 text-white">
                    <h4 class="font-bold text-sm">Preview Video</h4>
                    <p class="text-xs text-gray-400 mt-1">ID: {{ $video->youtube_id }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
