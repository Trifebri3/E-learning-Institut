@extends('participant.layouts.app')

@section('title', $resource->title)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('participant.materi.index') }}"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Perpustakaan
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                            <span>{{ $resource->kelas->program->title }}</span>
                            <i class="fas fa-chevron-right text-[10px]"></i>
                            <span>{{ $resource->kelas->title }}</span>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                            {{ $resource->title }}
                        </h1>
                    </div>

                    @php $isOpened = $resource->users->contains(auth()->user()->id); @endphp
                    @if($isOpened)
                        <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full text-xs font-bold border border-green-100 dark:border-green-800">
                            <i class="fas fa-check-circle mr-1.5"></i> Sudah Diakses
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-full text-xs font-bold border border-gray-200 dark:border-gray-600">
                            <i class="fas fa-circle mr-1.5 text-[8px]"></i> Baru
                        </span>
                    @endif
                </div>

                @if($resource->description)
                    <div class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed max-w-3xl">
                        {{ $resource->description }}
                    </div>
                @endif
            </div>

            <div class="p-6 md:p-8 bg-gray-50/50 dark:bg-gray-900/30 min-h-[400px]">

                @if($resource->file_path)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-1 shadow-sm mb-6">
                        <iframe src="{{ asset('storage/' . $resource->file_path) }}"
                                class="w-full h-[500px] md:h-[700px] rounded-lg bg-gray-100"
                                frameborder="0">
                        </iframe>
                    </div>

                    <div class="flex justify-center">
                        <a href="{{ asset('storage/' . $resource->file_path) }}" download
                           class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-white font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm">
                            <i class="fas fa-download mr-2 text-primary-600"></i> Download Materi
                        </a>
                    </div>
                @endif

                @if($resource->external_link)
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-4 text-blue-600 dark:text-blue-400">
                            <i class="fas fa-external-link-alt text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Materi Eksternal</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 max-w-md">
                            Materi ini dihosting di platform luar. Klik tombol di bawah untuk mengaksesnya.
                        </p>
                        <a href="{{ $resource->external_link }}" target="_blank"
                           class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                            Buka Tautan <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                        <p class="text-xs text-gray-400 mt-4 break-all">{{ $resource->external_link }}</p>
                    </div>
                @endif

            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-400 flex justify-between items-center">
                <span>Diunggah {{ $resource->created_at->diffForHumans() }}</span>
                <span>ID: #{{ $resource->id }}</span>
            </div>

        </div>
    </div>
</div>
@endsection
