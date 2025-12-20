@extends('participant.layouts.app')

@section('title', 'Data Profil Saya')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="text-center lg:text-left">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Profil Saya
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">
                        Kelola informasi profil dan data pribadi Anda
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-end">
                    <a href="{{ route('profile-data.edit') }}"
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-edit mr-3"></i>
                        Edit Profil
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 font-semibold rounded-xl transition-all duration-300">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Progress Indicator -->
            @php
                $requiredFields = [
                    'name', 'nama_panggilan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                    'kewarganegaraan', 'agama', 'golongan_darah', 'deskripsi_singkat',
                    'jenis_identitas', 'nomor_identitas', 'nomor_hp', 'email_cadangan',
                    'kontak_darurat_nama', 'kontak_darurat_hubungan', 'kontak_darurat_nomor',
                    'provinsi_id', 'kabupaten_kota', 'kecamatan', 'kelurahan_desa', 'rt_rw',
                    'kode_pos', 'alamat_lengkap', 'status_peserta', 'pendidikan_terakhir',
                    'nama_sekolah_kampus', 'jurusan', 'nisn_nim', 'pekerjaan', 'instansi_perusahaan', 'jabatan'
                ];
                $filledFields = 0;
                foreach ($requiredFields as $field) {
                    $value = old($field, $profile->$field ?? $user->$field ?? '');
                    if (!empty($value)) {
                        $filledFields++;
                    }
                }
                $completionPercentage = ($filledFields / count($requiredFields)) * 100;
            @endphp

            <!-- Completion Status Card -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status Kelengkapan Data</h3>
                    @if($completionPercentage == 100)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            <i class="fas fa-check-circle mr-1"></i> Lengkap
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Belum Lengkap
                        </span>
                    @endif
                </div>

                <div class="mb-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500 ease-out"
                         style="width: {{ $completionPercentage }}%"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Kelengkapan data: {{ round($completionPercentage) }}%
                    ({{ $filledFields }}/{{ count($requiredFields) }} field terisi)
                </p>
            </div>
        </div>
<!-- Section 2: Minat Program -->
<div class="border-b border-gray-200 dark:border-gray-700 pb-6 mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2"></span>
            Invitation Code
        </h3>
        <button type="button" id="btnEditMinat" onclick="toggleMinat(true)" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 transition-colors">
            <i class="fas fa-pencil-alt"></i> Ubah
        </button>
    </div>

    <div id="viewMinat" class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        {{ $profile->minat_program ?? '-' }}
    </div>

    <form id="formMinat" action="{{ route('profile.minat.update') }}" method="POST" class="hidden mt-2">
        @csrf
        @method('PATCH')
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Minat Program *</label>
            <input type="text" name="minat_program" id="inputMinat"
                   value="{{ old('minat_program', $profile->minat_program) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg">Simpan</button>
            <button type="button" onclick="toggleMinat(false)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg">Batal</button>
        </div>
    </form>
</div>

<script>
    function toggleMinat(isEditing) {
        const viewDiv = document.getElementById('viewMinat');
        const formDiv = document.getElementById('formMinat');
        const btnEdit = document.getElementById('btnEditMinat');

        if (isEditing) {
            viewDiv.classList.add('hidden');
            btnEdit.classList.add('hidden');
            formDiv.classList.remove('hidden');
            document.getElementById('inputMinat').focus();
        } else {
            viewDiv.classList.remove('hidden');
            btnEdit.classList.remove('hidden');
            formDiv.classList.add('hidden');
        }
    }

    // Cek error dari laravel agar form tetap terbuka saat ada error
    @if($errors->has('minat_program'))
        toggleMinat(true);
    @endif
</script>


        <!-- Profile Sections Accordion -->
        <div class="space-y-4" x-data="{ openSection: 'personal' }">

            <!-- Section 1: Data Pribadi -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                <button
                    @click="openSection = openSection === 'personal' ? '' : 'personal'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Pribadi</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Informasi identitas dan data diri</p>
                        </div>


                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 font-medium">
                            {{ $profile->nama_panggilan ? 'Terisi' : 'Kosong' }}
                        </span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                           :class="{ 'rotate-180': openSection === 'personal' }"></i>
                    </div>
                </button>

                <div x-show="openSection === 'personal'" x-collapse class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $user->name ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Panggilan</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->nama_panggilan ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->jenis_kelamin ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tempat Lahir</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->tempat_lahir ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    @if($profile->tanggal_lahir)
                                        {{ \Carbon\Carbon::parse($profile->tanggal_lahir)->translatedFormat('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Agama</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->agama ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Golongan Darah</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->golongan_darah ?? '-' }}
                                </p>
                            </div>
                                <div class="space-y-1">
        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Minat Program</label>
        <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
            {{ $profile->minat_program ?? '-' }}
        </p>
    </div>
                            <div class="md:col-span-2 lg:col-span-3 space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi Singkat</label>
                                <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg leading-relaxed">
                                    {{ $profile->deskripsi_singkat ?? 'Belum ada deskripsi' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Identitas & Kontak -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                <button
                    @click="openSection = openSection === 'identity' ? '' : 'identity'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-id-card text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Identitas & Kontak</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Informasi kontak dan identitas resmi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300 font-medium">
                            {{ $profile->nomor_identitas ? 'Terisi' : 'Kosong' }}
                        </span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                           :class="{ 'rotate-180': openSection === 'identity' }"></i>
                    </div>
                </button>

                <div x-show="openSection === 'identity'" x-collapse class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Identitas -->
                            <div class="space-y-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="fas fa-fingerprint text-purple-500"></i>
                                    Identitas Resmi
                                </h4>
                                <div class="space-y-3">
                                    <div class="space-y-1">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Identitas</label>
                                        <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                            {{ $profile->jenis_identitas ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Identitas</label>
                                        <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg font-mono">
                                            {{ $profile->nomor_identitas ? '••••••••' . substr($profile->nomor_identitas, -4) : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak -->
                            <div class="space-y-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="fas fa-phone text-green-500"></i>
                                    Informasi Kontak
                                </h4>
                                <div class="space-y-3">
                                    <div class="space-y-1">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor HP</label>
                                        <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                            {{ $profile->nomor_hp ? '••••••••' . substr($profile->nomor_hp, -4) : '-' }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Cadangan</label>
                                        <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                            {{ $profile->email_cadangan ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Alamat -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                <button
                    @click="openSection = openSection === 'address' ? '' : 'address'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-home text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alamat Tempat Tinggal</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Lokasi dan detail alamat lengkap</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300 font-medium">
                            {{ $profile->alamat_lengkap ? 'Terisi' : 'Kosong' }}
                        </span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                           :class="{ 'rotate-180': openSection === 'address' }"></i>
                    </div>
                </button>

                <div x-show="openSection === 'address'" x-collapse class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Provinsi</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->provinsi->nama ?? ($profile->kabupaten_kota ?? '-') }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kabupaten/Kota</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->kabupaten_kota ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kecamatan</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->kecamatan ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kelurahan/Desa</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->kelurahan_desa ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">RT/RW</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->rt_rw ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kode Pos</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->kode_pos ?? '-' }}
                                </p>
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Lengkap</label>
                                <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-750 px-3 py-3 rounded-lg leading-relaxed">
                                    {{ $profile->alamat_lengkap ?? 'Belum ada alamat lengkap' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Pendidikan & Pekerjaan -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                <button
                    @click="openSection = openSection === 'education' ? '' : 'education'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pendidikan & Pekerjaan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat pendidikan dan pekerjaan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300 font-medium">
                            {{ $profile->status_peserta ? 'Terisi' : 'Kosong' }}
                        </span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                           :class="{ 'rotate-180': openSection === 'education' }"></i>
                    </div>
                </button>

                <div x-show="openSection === 'education'" x-collapse class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status Peserta</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->status_peserta ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Pendidikan Terakhir</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->pendidikan_terakhir ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Sekolah/Kampus</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->nama_sekolah_kampus ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jurusan</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->jurusan ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">NISN/NIM</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->nisn_nim ? '••••••••' . substr($profile->nisn_nim, -4) : '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Pekerjaan</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->pekerjaan ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Instansi/Perusahaan</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->instansi_perusahaan ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</label>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold bg-gray-50 dark:bg-gray-750 px-3 py-2 rounded-lg">
                                    {{ $profile->jabatan ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Dokumen -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-xl">
                <button
                    @click="openSection = openSection === 'documents' ? '' : 'documents'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200"
                >
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-alt text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dokumen Pendukung</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Foto dan dokumen identitas</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 font-medium">
                            {{ ($profile->pas_foto_path && $profile->scan_ktp_path) ? 'Lengkap' : 'Perlu Dilengkapi' }}
                        </span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                           :class="{ 'rotate-180': openSection === 'documents' }"></i>
                    </div>
                </button>

                <div x-show="openSection === 'documents'" x-collapse class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Pas Foto -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="fas fa-camera text-3xl text-gray-400 mb-2"></i>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Pas Foto</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Foto formal terbaru</p>
                                </div>

                                @if ($profile->pas_foto_path)
                                    <div class="border-2 border-green-200 dark:border-green-800 rounded-xl p-4 bg-green-50 dark:bg-green-900/20">
                                        <img src="{{ asset('storage/' . $profile->pas_foto_path) }}"
                                             alt="Pas Foto"
                                             class="w-32 h-32 rounded-lg object-cover mx-auto shadow-md">
                                        <div class="mt-3">
                                            <a href="{{ asset('storage/' . $profile->pas_foto_path) }}"
                                               target="_blank"
                                               class="inline-flex items-center text-sm text-green-600 hover:text-green-500 dark:text-green-400 font-medium">
                                                <i class="fas fa-external-link-alt mr-2"></i> Lihat Full Size
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center bg-gray-50 dark:bg-gray-900/50">
                                        <i class="fas fa-user-slash text-4xl text-gray-400 mb-3"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pas foto</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Scan KTP -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="fas fa-id-card text-3xl text-gray-400 mb-2"></i>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Scan Identitas</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">KTP/Kartu Pelajar</p>
                                </div>

                                @if ($profile->scan_ktp_path)
                                    <div class="border-2 border-blue-200 dark:border-blue-800 rounded-xl p-4 bg-blue-50 dark:bg-blue-900/20">
                                        <div class="w-32 h-32 mx-auto bg-white dark:bg-gray-800 rounded-lg flex items-center justify-center shadow-md">
                                            <i class="fas fa-file-pdf text-3xl text-blue-500"></i>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ asset('storage/' . $profile->scan_ktp_path) }}"
                                               target="_blank"
                                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 font-medium">
                                                <i class="fas fa-external-link-alt mr-2"></i> Lihat Dokumen
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center bg-gray-50 dark:bg-gray-900/50">
                                        <i class="fas fa-file-excel text-4xl text-gray-400 mb-3"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada dokumen</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mt-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Perlu memperbarui data?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pastikan informasi Anda selalu terbaru</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('profile-data.edit') }}"
                       class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-sync-alt mr-3"></i>
                        Update Profil
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-750 font-semibold rounded-xl transition-all duration-300">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Ke Dashboard
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-lift:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }

    .gradient-border {
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #4f46e5, #7e22ce) border-box;
        border: 2px solid transparent;
    }

    .dark .gradient-border {
        background: linear-gradient(#1f2937, #1f2937) padding-box,
                    linear-gradient(45deg, #4f46e5, #7e22ce) border-box;
        border: 2px solid transparent;
    }
</style>
@endpush

@push('scripts')
<script>
    // Smooth scroll to section if there's a hash in URL
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to cards
        const cards = document.querySelectorAll('.bg-white, .bg-gray-50');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Handle URL hash for direct section opening
        const urlHash = window.location.hash.substring(1);
        if (urlHash) {
            const section = document.querySelector(`[data-section="${urlHash}"]`);
            if (section) {
                setTimeout(() => {
                    section.click();
                }, 500);
            }
        }
    });
</script>
@endpush
