@extends('participant.layouts.app')

@section('title', $program->title . ' - Institut Hijau Indonesia')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="{{ route('participant.program.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="relative h-64 md:h-80 bg-gray-100 dark:bg-gray-900">
                @if ($program->banner_path)
                    <img src="{{ Storage::url($program->banner_path) }}" class="w-full h-full object-cover" alt="{{ $program->title }}">
                @else
                    <img src="{{ asset('images/defaultbannerprogram.svg') }}" class="w-full h-full object-cover opacity-50" alt="Default Banner">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
            </div>

            <div class="absolute bottom-0 left-0 w-full p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-end gap-6">
                    <div class="hidden md:block flex-shrink-0 bg-white p-2 rounded-xl shadow-lg">
                        @if ($program->logo_path)
                            <img src="{{ Storage::url($program->logo_path) }}" class="w-20 h-20 object-contain rounded-lg" alt="Logo">
                        @else
                            <img src="{{ asset('images/defaultlogoprogram.svg') }}" class="w-20 h-20 object-contain rounded-lg" alt="Default Logo">
                        @endif
                    </div>

                    <div class="flex-1 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary-600 text-white border border-primary-500">
                                {{ $program->status ?? 'Program' }}
                            </span>
                            @if($program->is_joined)
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-600 text-white border border-green-500">
                                    <i class="fas fa-check mr-1"></i> Terdaftar
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl md:text-4xl font-bold leading-tight mb-2">{{ $program->title }}</h1>
                        <p class="text-gray-300 text-sm md:text-base line-clamp-1 max-w-2xl">
                            {{ $program->deskripsi_singkat ?? 'Program pengembangan kompetensi.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 flex items-center justify-center">
                            <i class="fas fa-align-left text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Tentang Program</h2>
                    </div>

                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed">
                        @if($program->deskripsi_lengkap)
                            {!! nl2br(e($program->deskripsi_lengkap)) !!}
                        @else
                            <p class="italic text-gray-400">Deskripsi lengkap belum tersedia untuk program ini.</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 text-center shadow-sm">
                        <span class="block text-2xl font-bold text-primary-600 mb-1">{{ $program->kelas_count ?? 0 }}</span>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Kelas</span>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 text-center shadow-sm">
                        <span class="block text-2xl font-bold text-primary-600 mb-1">{{ $program->materials_count ?? 0 }}</span>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Materi</span>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 text-center shadow-sm">
                        <span class="block text-2xl font-bold text-primary-600 mb-1">{{ $program->assignments_count ?? 0 }}</span>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Tugas</span>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 text-center shadow-sm">
                        <span class="block text-2xl font-bold text-primary-600 mb-1">{{ $program->participants_count ?? 0 }}</span>
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Peserta</span>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg sticky top-24">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Status Keikutsertaan</h3>

                    @if($program->is_joined)
                        <div class="mb-6">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress Belajar</span>
                                <span class="text-sm font-bold text-primary-600">{{ $program->user_progress ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-500" style="width: {{ $program->user_progress ?? 0 }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('participant.kelas.index') }}" class="block w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-center rounded-xl shadow-md transition-all hover:-translate-y-0.5 mb-3">
                            Lanjutkan Belajar
                        </a>
                    @else
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Anda belum terdaftar di program ini.</p>
                            <a href="{{ route('participant.redeem.form') }}" class="block w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-center rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                                Redeem Akses
                            </a>
                        </div>
                    @endif

                    @if($program->redeem_code)
                        <div class="mt-6 pt-6 border-t border-dashed border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-center text-gray-500 mb-2">Punya Kode Akses?</p>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center border border-gray-200 dark:border-gray-600">
                                <span class="font-mono text-sm font-bold text-gray-700 dark:text-gray-200 select-all">silahkan daftarkan diri ke penyelenggara program</span>
                            </div>
                            <p class="text-[10px] text-gray-400 text-center mt-2">Gunakan kode yang di berikan penyelenggara pada menu Redeem.</p>
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Detail</h3>

                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start justify-between">
                            <span class="text-gray-500">Tanggal Mulai</span>
                            <span class="font-medium text-gray-900 dark:text-white text-right">
                                {{ $program->waktu_mulai ? \Carbon\Carbon::parse($program->waktu_mulai)->format('d M Y') : '-' }}
                            </span>
                        </li>
                        <li class="flex items-start justify-between">
                            <span class="text-gray-500">Tanggal Selesai</span>
                            <span class="font-medium text-gray-900 dark:text-white text-right">
                                {{ $program->waktu_selesai ? \Carbon\Carbon::parse($program->waktu_selesai)->format('d M Y') : '-' }}
                            </span>
                        </li>
                        <li class="flex items-start justify-between">
                            <span class="text-gray-500">Lokasi</span>
                            <span class="font-medium text-gray-900 dark:text-white text-right">{{ $program->lokasi ?? 'Online' }}</span>
                        </li>
                        <li class="flex items-start justify-between">
                            <span class="text-gray-500">Kuota Peserta</span>
                            <span class="font-medium text-gray-900 dark:text-white text-right">{{ $program->kuota ?? '-' }}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
