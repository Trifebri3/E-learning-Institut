@extends('participant.layouts.app')

@section('title', 'Daftar Narasumber')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center shadow-sm">
                        <i class="fas fa-users text-lg"></i>
                    </span>
                    Narasumber & Pemateri
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base ml-14">
                    Kenali para ahli dan praktisi yang membimbing Anda dalam program ini.
                </p>
            </div>
        </div>

        @if(collect($narasumbers)->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 px-6 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 text-gray-300 dark:text-gray-500">
                    <i class="fas fa-user-slash text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Narasumber</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center">
                    Data narasumber belum tersedia untuk saat ini.
                </p>
            </div>
        @else
            <div class="space-y-12">
                @foreach($narasumbers as $programTitle => $listNarasumber)
                    <div class="program-section">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-1.5 h-8 bg-primary-500 rounded-full"></div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-none">
                                    {{ $programTitle }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ count($listNarasumber) }} Narasumber
                                </p>
                            </div>
                            <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700 ml-4"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($listNarasumber as $narasumber)
                                <x-narasumber-card :narasumber="$narasumber" />
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
