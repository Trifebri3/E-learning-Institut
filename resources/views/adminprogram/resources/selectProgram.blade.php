@extends('adminprogram.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
            Pilih Program
        </h1>
        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 dark:text-gray-400 sm:mt-4">
            Silakan pilih program yang ingin Anda kelola materinya.
        </p>
    </div>

    @if($programs->count() > 0)
        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @foreach($programs as $program)
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">

<div class="flex-shrink-0">
    @if ($program->logo_path)
        <img src="{{ asset('storage/app/public/' . $program->logo_path) }}"
             class="h-48 w-full object-contain rounded-lg"
             alt="{{ $program->title }} Logo">
    @else
        <img src="{{ asset('images/defaultlogoprogram.svg') }}"
             class="h-48 w-full object-contain rounded-lg"
             alt="Default Logo">
    @endif
</div>



                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                Program
                            </p>
                            <a href="{{ route('adminprogram.resources.indexByProgram', $program->id) }}" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $program->title }}
                                </p>
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-400 line-clamp-3">
                                    {{ $program->description ?? 'Tidak ada deskripsi.' }}
                                </p>
                            </a>
                        </div>

                        <div class="mt-6 flex items-center">
                            <a href="{{ route('adminprogram.resources.indexByProgram', $program->id) }}"
                               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Kelola Materi
                                <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada program</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum ditugaskan ke program manapun.</p>
        </div>
    @endif
</div>
@endsection
