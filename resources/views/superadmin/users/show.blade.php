@extends('superadmin.layouts.app')

@section('title', 'Detail Pengguna: ' . $user->name)

@section('content')
<div class="container mx-auto p-6 max-w-5xl">

    <!-- Header & Tombol Kembali -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.users.index') }}" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                <i class="fas fa-arrow-left text-gray-600 dark:text-gray-300"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Pengguna</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow text-sm font-medium">
                <i class="fas fa-edit mr-2"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: Foto & Info Akun Utama -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Kartu Foto Profil -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 text-center border border-gray-200 dark:border-gray-700">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    @if($user->profile && $user->profile->pas_foto_path)
                        <img src="{{ Storage::url($user->profile->pas_foto_path) }}"
                             alt="Foto Profil"
                             class="w-full h-full rounded-full object-cover border-4 border-gray-100 dark:border-gray-700 shadow-sm">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=random"
                             class="w-full h-full rounded-full object-cover border-4 border-gray-100 dark:border-gray-700 shadow-sm">
                    @endif
                </div>

                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ $user->email }}</p>

                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                    {{ $user->role == 'superadmin' ? 'bg-red-100 text-red-800' :
                      ($user->role == 'admin_program' ? 'bg-yellow-100 text-yellow-800' :
                      ($user->role == 'instructor' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800')) }}">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 text-left text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Bergabung:</span>
                        <span class="font-medium dark:text-gray-300">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status Profil:</span>
                        @if($user->profile && $user->profile->is_complete)
                            <span class="text-green-600 font-bold">Lengkap</span>
                        @else
                            <span class="text-red-500 font-bold">Belum Lengkap</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dokumen Identitas -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-id-card mr-2 text-blue-500"></i> Dokumen Identitas
                </h3>

                @if($user->profile && $user->profile->scan_ktp_path)
                    <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 group relative">
                        <img src="{{ Storage::url($user->profile->scan_ktp_path) }}" alt="Scan KTP" class="w-full h-auto object-cover">

                        <!-- Overlay Hover untuk Download/View -->
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <a href="{{ Storage::url($user->profile->scan_ktp_path) }}" target="_blank" class="px-4 py-2 bg-white text-gray-900 rounded-lg font-bold text-sm hover:bg-gray-100">
                                <i class="fas fa-eye mr-1"></i> Lihat Penuh
                            </a>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">Klik gambar untuk memperbesar</p>
                @else
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                        <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-500">Scan identitas belum diunggah.</p>
                    </div>
                @endif

                
            </div>
        </div>

        <!-- KOLOM KANAN: Detail Data Diri Lengkap -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Biodata Lengkap</h3>
                </div>

                <div class="p-6">
                    @if($user->profile)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            <!-- Grup: Identitas Diri -->
                            <div class="md:col-span-2 pb-2 border-b border-gray-100 dark:border-gray-700 mb-2">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Data Pribadi</h4>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Nama Panggilan</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->nama_panggilan ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Jenis Kelamin</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->jenis_kelamin ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Tempat, Tanggal Lahir</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">
                                    {{ $user->profile->tempat_lahir ?? '-' }},
                                    {{ $user->profile->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Agama</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->agama ?? '-' }}</p>
                            </div>

                            <!-- Grup: Kontak & Identitas -->
                            <div class="md:col-span-2 pb-2 border-b border-gray-100 dark:border-gray-700 mb-2 mt-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kontak & Identitas</h4>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Nomor HP / WA</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->nomor_hp ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Email Cadangan</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->email_cadangan ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Jenis Identitas</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->jenis_identitas ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Nomor Identitas</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->nomor_identitas ?? '-' }}</p>
                            </div>

                            <!-- Grup: Alamat -->
                            <div class="md:col-span-2 pb-2 border-b border-gray-100 dark:border-gray-700 mb-2 mt-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat Domisili</h4>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Provinsi</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">
                                    {{-- Asumsi Anda punya relasi 'provinsi' di model Profile --}}
                                    {{ $user->profile->provinsi ? $user->profile->provinsi->nama : '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Kabupaten / Kota</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->kabupaten_kota ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 block mb-1">Alamat Lengkap</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->alamat_lengkap ?? '-' }}</p>
                            </div>

                            <!-- Grup: Pendidikan/Pekerjaan -->
                            <div class="md:col-span-2 pb-2 border-b border-gray-100 dark:border-gray-700 mb-2 mt-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pendidikan / Pekerjaan</h4>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Status Peserta</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->status_peserta ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Institusi / Sekolah</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $user->profile->instansi_perusahaan ?? $user->profile->nama_sekolah_kampus ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 block mb-1">Bio Singkat</label>
                                <p class="font-medium text-gray-900 dark:text-gray-200 text-sm italic">
                                    "{{ $user->profile->deskripsi_singkat ?? '-' }}"
                                </p>
                            </div>

                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Data profil belum diisi oleh pengguna.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
