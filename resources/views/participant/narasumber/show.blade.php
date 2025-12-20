@extends('participant.layouts.app')

@section('title', $narasumber->nama)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-24">

                    <div class="h-24 bg-gray-100 dark:bg-gray-700"></div>

                    <div class="px-6 pb-6 text-center relative">
                        <div class="relative -mt-12 mb-4 inline-block">
                            <div class="w-24 h-24 rounded-full p-1 bg-white dark:bg-gray-800 shadow-sm mx-auto">
                                <img src="{{ $narasumber->foto_path ? Storage::url($narasumber->foto_path) : 'https://ui-avatars.com/api/?name=' . urlencode($narasumber->nama) . '&size=128&background=random' }}"
                                     class="w-full h-full rounded-full object-cover bg-gray-100 dark:bg-gray-700"
                                     alt="{{ $narasumber->nama }}">
                            </div>
                            @if($narasumber->is_verified)
                                <div class="absolute bottom-1 right-1 bg-blue-500 text-white rounded-full p-1 border-2 border-white dark:border-gray-800 w-6 h-6 flex items-center justify-center" title="Terverifikasi">
                                    <i class="fas fa-check text-[10px]"></i>
                                </div>
                            @endif
                        </div>

                        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                            {{ $narasumber->nama }}
                        </h1>
                        <p class="text-sm text-primary-600 dark:text-primary-400 font-medium mb-4">
                            {{ $narasumber->jabatan }}
                        </p>

                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 mb-6">
                            <i class="fas fa-graduation-cap text-gray-400 text-xs"></i>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                {{ $narasumber->program->title }}
                            </span>
                        </div>

                        @if($narasumber->kontak)
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mt-2">
                                <a href="{{ (str_contains($narasumber->kontak, '@')) ? 'mailto:'.$narasumber->kontak : $narasumber->kontak }}"
                                   target="_blank"
                                   class="flex items-center justify-center gap-2 w-full py-2.5 px-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-primary-600 transition-all shadow-sm text-sm font-medium">
                                    <i class="fas fa-link text-xs"></i>
                                    Hubungi
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="far fa-user text-primary-500"></i> Tentang Narasumber
                    </h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($narasumber->deskripsi)) !!}
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                        @if($narasumber->pengalaman)
                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Pengalaman</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $narasumber->pengalaman }}</span>
                        </div>
                        @endif
                        @if($narasumber->spesialisasi)
                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Spesialisasi</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $narasumber->spesialisasi }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                @if($kelasDiajar->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-chalkboard-teacher text-primary-500"></i> Kelas yang Diampu
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($kelasDiajar as $kelas)
                            <a href="{{ route('participant.kelas.show', $kelas->id) }}"
                               class="group flex items-start gap-4 p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">

                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex flex-col items-center justify-center text-center overflow-hidden">
                                    <span class="text-[10px] bg-gray-100 dark:bg-gray-600 w-full text-gray-500 uppercase font-bold py-0.5">
                                        {{ \Carbon\Carbon::parse($kelas->tanggal)->translatedFormat('M') }}
                                    </span>
                                    <span class="text-lg font-bold text-gray-800 dark:text-white leading-none pt-1">
                                        {{ \Carbon\Carbon::parse($kelas->tanggal)->format('d') }}
                                    </span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors mb-1">
                                        {{ $kelas->title }}
                                    </h4>
                                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }} WIB
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $kelas->tempat ?? 'Online' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-shrink-0 self-center">
                                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-primary-500 transition-colors"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
