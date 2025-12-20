@extends('instructor.layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Daftar Kelas
        </h1>
        </div>

    @if($kelasList->count() > 0)
        <ul class="space-y-4">
            @foreach($kelasList as $kelas)
                <li class="p-5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex flex-col sm:flex-row justify-between items-center transition-colors duration-200">

                    <div class="mb-3 sm:mb-0">
                        <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $kelas->title }}
                            {{-- Pastikan pakai 'title' jika mengikuti controller sebelumnya, atau 'nama' jika belum diubah di DB --}}
                        </span>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $kelas->resources_count ?? 0 }} Materi tersedia
                        </p>
                    </div>

                    <div class="flex space-x-3">
                        {{-- Catatan: Link ini mengarah ke indexByProgram, pastikan logikanya sesuai keinginan Anda --}}
                        <a href="{{ route('adminprogram.resources.indexByProgram', $programId) }}"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out shadow-sm">
                            Lihat Materi
                        </a>

                        <a href="{{ route('adminprogram.resources.create', $kelas->id) }}"
                           class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-center py-10 px-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada kelas</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulailah dengan menambahkan kelas baru ke program ini.</p>
        </div>
    @endif
@endsection
