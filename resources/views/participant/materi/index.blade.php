@extends('participant.layouts.app')

@section('title', 'Perpustakaan Materi')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                        <i class="fas fa-book-open text-lg"></i>
                    </span>
                    Perpustakaan Materi
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                    Koleksi lengkap materi pembelajaran dari semua program Anda.
                </p>
            </div>
        </div>

        @if($groupedResources->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-300 dark:text-gray-500">
                    <i class="fas fa-folder-open text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Materi</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center max-w-xs">
                    Materi akan tersedia secara otomatis setelah Anda terdaftar dalam program pembelajaran.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($groupedResources as $programTitle => $resources)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full overflow-hidden group">

                        <div class="p-6 pb-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight line-clamp-2 group-hover:text-primary-600 transition-colors">
                                    {{ $programTitle }}
                                </h3>
                                <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center text-gray-400 flex-shrink-0">
                                    <i class="fas fa-graduation-cap text-xs"></i>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1.5 bg-white dark:bg-gray-700 px-2 py-1 rounded border border-gray-100 dark:border-gray-600">
                                    <i class="fas fa-file-alt text-primary-500"></i>
                                    {{ $resources->count() }} Materi
                                </span>
                                @php $openedCount = $resources->where('users', '!=', null)->count(); @endphp
                                @if($openedCount > 0)
                                <span class="flex items-center gap-1.5 bg-white dark:bg-gray-700 px-2 py-1 rounded border border-gray-100 dark:border-gray-600">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    {{ $openedCount }} Diakses
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 space-y-2 flex-1">
                            @foreach($resources->take(3) as $resource)
                                @include('participant.materi.partials.item', ['resource' => $resource])
                            @endforeach

                            @if($resources->count() > 3)
                                <div x-data="{ expanded: false }">
                                    <div x-show="expanded" x-collapse class="space-y-2 mt-2 pt-2 border-t border-dashed border-gray-100 dark:border-gray-700">
                                        @foreach($resources->skip(3) as $resource)
                                            @include('participant.materi.partials.item', ['resource' => $resource])
                                        @endforeach
                                    </div>

                                    <button @click="expanded = !expanded"
                                            class="w-full mt-3 py-2 text-xs font-bold text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 flex items-center justify-center gap-2 transition-colors border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-xl hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <span x-text="expanded ? 'Sembunyikan' : 'Lihat {{ $resources->count() - 3 }} Lainnya'"></span>
                                        <i class="fas fa-chevron-down transition-transform duration-200" :class="{'rotate-180': expanded}"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

{{-- Partial Item untuk menghindari duplikasi kode (Opsional: bisa dipisah ke file view sendiri) --}}
{{-- Buat file baru: resources/views/participant/materi/partials/item.blade.php --}}
{{-- Jika tidak ingin buat file baru, cukup paste kode di bawah ini di dalam loop --}}
