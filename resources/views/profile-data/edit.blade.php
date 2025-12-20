@extends('participant.layouts.app1')

@section('title', 'Lengkapi Data Diri')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lengkapi Data Diri') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Lengkapi semua data diri Anda untuk dapat mengakses seluruh fitur sistem
            </p>

            <!-- Progress Indicator -->
            @php
                $requiredFields = [
                    'name', 'nama_panggilan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                    'kewarganegaraan', 'agama', 'golongan_darah', 'deskripsi_singkat',
                    'jenis_identitas', 'nomor_identitas', 'nomor_hp', 'email_cadangan',
                    'kontak_darurat_nama', 'kontak_darurat_hubungan', 'kontak_darurat_nomor',
                    'provinsi_id', 'kabupaten_kota', 'kecamatan', 'kelurahan_desa', 'rt_rw',
                    'kode_pos', 'alamat_lengkap', 'status_peserta', 'pendidikan_terakhir',
                    'nama_sekolah_kampus', 'jurusan', 'nisn_nim', 'pekerjaan', 'instansi_perusahaan', 'jabatan', 'minat_program'
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

            <div class="mt-4 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div class="bg-primary-600 h-2.5 rounded-full"
                     style="width: {{ $completionPercentage }}%"></div>
            </div>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                Kelengkapan data: {{ round($completionPercentage) }}%
                ({{ $filledFields }}/{{ count($requiredFields) }} field terisi)
            </p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900/20 dark:text-green-300 border border-green-200 dark:border-green-800" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-medium">Sukses!</span> {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900/20 dark:text-red-300 border border-red-200 dark:border-red-800" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900/20 dark:text-red-300 border border-red-200 dark:border-red-800" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-medium">Oops! Ada kesalahan:</span>
                </div>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-6 bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="max-w-4xl">

                <form method="post" action="{{ route('profile-data.update') }}" enctype="multipart/form-data" class="space-y-8" id="profileForm">
                    @csrf
                    @method('put')

                    <!-- Section 1: Data Pribadi -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2">1</span>
                            Data Pribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Lengkap (sesuai KTP) <span class="text-red-500">*</span>
                                </label>
                                <input id="name" name="name" type="text"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Panggilan -->
                            <div>
                                <label for="nama_panggilan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Panggilan <span class="text-red-500">*</span>
                                </label>
                                <input id="nama_panggilan" name="nama_panggilan" type="text"
                                       value="{{ old('nama_panggilan', $profile->nama_panggilan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('nama_panggilan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select id="jenis_kelamin" name="jenis_kelamin"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">Pilih...</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $profile->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input id="tempat_lahir" name="tempat_lahir" type="text"
                                       value="{{ old('tempat_lahir', $profile->tempat_lahir) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input id="tanggal_lahir" name="tanggal_lahir" type="date"
                                       value="{{ old('tanggal_lahir', $profile->tanggal_lahir) }}"
                                       required
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kewarganegaraan -->
                            <div>
                                <label for="kewarganegaraan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kewarganegaraan <span class="text-red-500">*</span>
                                </label>
                                <input id="kewarganegaraan" name="kewarganegaraan" type="text"
                                       value="{{ old('kewarganegaraan', $profile->kewarganegaraan ?? 'Indonesia') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kewarganegaraan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Agama -->
                            <div>
                                <label for="agama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Agama <span class="text-red-500">*</span>
                                </label>
                                <select id="agama" name="agama"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">Pilih...</option>
                                    <option value="Islam" {{ old('agama', $profile->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama', $profile->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ old('agama', $profile->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ old('agama', $profile->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ old('agama', $profile->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ old('agama', $profile->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                                @error('agama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Golongan Darah -->
                            <div>
                                <label for="golongan_darah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Golongan Darah <span class="text-red-500">*</span>
                                </label>
                                <select id="golongan_darah" name="golongan_darah"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">Pilih...</option>
                                    <option value="A" {{ old('golongan_darah', $profile->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('golongan_darah', $profile->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('golongan_darah', $profile->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('golongan_darah', $profile->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="Tidak Tahu" {{ old('golongan_darah', $profile->golongan_darah) == 'Tidak Tahu' ? 'selected' : '' }}>Tidak Tahu</option>
                                </select>
                                @error('golongan_darah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
            <!-- Minat Program -->
            <div class="md:col-span-2">
                <label for="minat_program" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Invitation Code <span class="text-red-500">*</span>
                </label>
                <input id="minat_program" name="minat_program" type="text"
                       value="{{ old('minat_program', $profile->minat_program) }}"
                       required
                       placeholder="Masukkan minat program Anda"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                @error('minat_program')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
                            <!-- Deskripsi Singkat -->
                            <div class="md:col-span-2">
                                <label for="deskripsi_singkat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deskripsi Singkat (Bio) <span class="text-red-500">*</span>
                                </label>
                                <textarea id="deskripsi_singkat" name="deskripsi_singkat"
                                          rows="3"
                                          required
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">{{ old('deskripsi_singkat', $profile->deskripsi_singkat) }}</textarea>
                                @error('deskripsi_singkat')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span id="deskripsi_count">0</span>/500 karakter
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Identitas -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2">2</span>
                            Identitas
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenis Identitas -->
                            <div>
                                <label for="jenis_identitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jenis Identitas <span class="text-red-500">*</span>
                                </label>
                                <select id="jenis_identitas" name="jenis_identitas"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">Pilih...</option>
                                    <option value="KTP" {{ old('jenis_identitas', $profile->jenis_identitas) == 'KTP' ? 'selected' : '' }}>KTP</option>
                                    <option value="Paspor" {{ old('jenis_identitas', $profile->jenis_identitas) == 'Paspor' ? 'selected' : '' }}>Paspor</option>
                                    <option value="SIM" {{ old('jenis_identitas', $profile->jenis_identitas) == 'SIM' ? 'selected' : '' }}>SIM</option>
                                    <option value="Kartu Pelajar" {{ old('jenis_identitas', $profile->jenis_identitas) == 'Kartu Pelajar' ? 'selected' : '' }}>Kartu Pelajar</option>
                                </select>
                                @error('jenis_identitas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Identitas -->
                            <div>
                                <label for="nomor_identitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor Identitas <span class="text-red-500">*</span>
                                </label>
                                <input id="nomor_identitas" name="nomor_identitas" type="text"
                                       value="{{ old('nomor_identitas', $profile->nomor_identitas) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('nomor_identitas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Kontak -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2">3</span>
                            Kontak
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nomor HP -->
                            <div>
                                <label for="nomor_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor HP (WhatsApp) <span class="text-red-500">*</span>
                                </label>
                                <input id="nomor_hp" name="nomor_hp" type="tel"
                                       value="{{ old('nomor_hp', $profile->nomor_hp) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('nomor_hp')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Cadangan -->
                            <div>
                                <label for="email_cadangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Cadangan <span class="text-red-500">*</span>
                                </label>
                                <input id="email_cadangan" name="email_cadangan" type="email"
                                       value="{{ old('email_cadangan', $profile->email_cadangan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('email_cadangan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Kontak Darurat -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2">4</span>
                            Kontak Darurat
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Nama Kontak Darurat -->
                            <div>
                                <label for="kontak_darurat_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Kontak Darurat <span class="text-red-500">*</span>
                                </label>
                                <input id="kontak_darurat_nama" name="kontak_darurat_nama" type="text"
                                       value="{{ old('kontak_darurat_nama', $profile->kontak_darurat_nama) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kontak_darurat_nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hubungan -->
                            <div>
                                <label for="kontak_darurat_hubungan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Hubungan <span class="text-red-500">*</span>
                                </label>
                                <input id="kontak_darurat_hubungan" name="kontak_darurat_hubungan" type="text"
                                       value="{{ old('kontak_darurat_hubungan', $profile->kontak_darurat_hubungan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kontak_darurat_hubungan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon Darurat -->
                            <div>
                                <label for="kontak_darurat_nomor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor Telepon Darurat <span class="text-red-500">*</span>
                                </label>
                                <input id="kontak_darurat_nomor" name="kontak_darurat_nomor" type="tel"
                                       value="{{ old('kontak_darurat_nomor', $profile->kontak_darurat_nomor) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kontak_darurat_nomor')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Alamat -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2">5</span>
                            Alamat
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Provinsi -->
                            <div>
                                <label for="provinsi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <select id="provinsi_id" name="provinsi_id"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach ($provinsiList as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('provinsi_id', $profile->provinsi_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('provinsi_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kabupaten/Kota -->
                            <div>
                                <label for="kabupaten_kota" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kabupaten / Kota <span class="text-red-500">*</span>
                                </label>
                                <input id="kabupaten_kota" name="kabupaten_kota" type="text"
                                       value="{{ old('kabupaten_kota', $profile->kabupaten_kota) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kabupaten_kota')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label for="kecamatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kecamatan <span class="text-red-500">*</span>
                                </label>
                                <input id="kecamatan" name="kecamatan" type="text"
                                       value="{{ old('kecamatan', $profile->kecamatan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kecamatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kelurahan/Desa -->
                            <div>
                                <label for="kelurahan_desa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kelurahan/Desa <span class="text-red-500">*</span>
                                </label>
                                <input id="kelurahan_desa" name="kelurahan_desa" type="text"
                                       value="{{ old('kelurahan_desa', $profile->kelurahan_desa) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kelurahan_desa')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- RT/RW -->
                            <div>
                                <label for="rt_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    RT/RW <span class="text-red-500">*</span>
                                </label>
                                <input id="rt_rw" name="rt_rw" type="text"
                                       value="{{ old('rt_rw', $profile->rt_rw) }}"
                                       required
                                       placeholder="001/002"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('rt_rw')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Pos -->
                            <div>
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kode Pos <span class="text-red-500">*</span>
                                </label>
                                <input id="kode_pos" name="kode_pos" type="text"
                                       value="{{ old('kode_pos', $profile->kode_pos) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('kode_pos')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat Lengkap -->
                            <div class="md:col-span-2">
                                <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea id="alamat_lengkap" name="alamat_lengkap"
                                          rows="3"
                                          required
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">{{ old('alamat_lengkap', $profile->alamat_lengkap) }}</textarea>
                                @error('alamat_lengkap')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span id="alamat_count">0</span>/500 karakter
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Pendidikan & Pekerjaan -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2">6</span>
                            Pendidikan & Pekerjaan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status Peserta -->
                            <div>
                                <label for="status_peserta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status Peserta <span class="text-red-500">*</span>
                                </label>
                                <select id="status_peserta" name="status_peserta"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">Pilih Status...</option>
                                    <option value="Pelajar/Mahasiswa" {{ old('status_peserta', $profile->status_peserta) == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>Pelajar / Mahasiswa</option>
                                    <option value="Profesional" {{ old('status_peserta', $profile->status_peserta) == 'Profesional' ? 'selected' : '' }}>Profesional</option>
                                    <option value="Lainnya" {{ old('status_peserta', $profile->status_peserta) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('status_peserta')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pendidikan Terakhir -->
                            <div>
                                <label for="pendidikan_terakhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pendidikan Terakhir <span class="text-red-500">*</span>
                                </label>
                                <select id="pendidikan_terakhir" name="pendidikan_terakhir"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">Pilih...</option>
                                    <option value="SD" {{ old('pendidikan_terakhir', $profile->pendidikan_terakhir) == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan_terakhir', $profile->pendidikan_terakhir) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('pendidikan_terakhir', $profile->pendidikan_terakhir) == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="D3" {{ old('pendidikan_terakhir', $profile->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('pendidikan_terakhir', $profile->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan_terakhir', $profile->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan_terakhir', $profile->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('pendidikan_terakhir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Sekolah/Kampus -->
                            <div>
                                <label for="nama_sekolah_kampus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Sekolah/Kampus <span class="text-red-500">*</span>
                                </label>
                                <input id="nama_sekolah_kampus" name="nama_sekolah_kampus" type="text"
                                       value="{{ old('nama_sekolah_kampus', $profile->nama_sekolah_kampus) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('nama_sekolah_kampus')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jurusan -->
                            <div>
                                <label for="jurusan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jurusan <span class="text-red-500">*</span>
                                </label>
                                <input id="jurusan" name="jurusan" type="text"
                                       value="{{ old('jurusan', $profile->jurusan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('jurusan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NISN/NIM -->
                            <div>
                                <label for="nisn_nim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    NISN/NIM <span class="text-red-500">*</span>
                                </label>
                                <input id="nisn_nim" name="nisn_nim" type="text"
                                       value="{{ old('nisn_nim', $profile->nisn_nim) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('nisn_nim')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pekerjaan -->
                            <div>
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <input id="pekerjaan" name="pekerjaan" type="text"
                                       value="{{ old('pekerjaan', $profile->pekerjaan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('pekerjaan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Instansi/Perusahaan -->
                            <div>
                                <label for="instansi_perusahaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Instansi/Perusahaan <span class="text-red-500">*</span>
                                </label>
                                <input id="instansi_perusahaan" name="instansi_perusahaan" type="text"
                                       value="{{ old('instansi_perusahaan', $profile->instansi_perusahaan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('instansi_perusahaan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jabatan -->
                            <div>
                                <label for="jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jabatan <span class="text-red-500">*</span>
                                </label>
                                <input id="jabatan" name="jabatan" type="text"
                                       value="{{ old('jabatan', $profile->jabatan) }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('jabatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Dokumen -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <span class="w-6 h-6 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm mr-2">7</span>
                            Dokumen
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- PAS FOTO -->
                            <div>
                                <label for="pas_foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pas Foto <span class="text-red-500">*</span>
                                </label>

                                @if ($profile->pas_foto_path)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $profile->pas_foto_path) }}"
                                             alt="Pas Foto"
                                             class="w-32 h-32 rounded-md object-cover border-2 border-gray-200 dark:border-gray-600">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Foto saat ini</p>
                                    </div>
                                @else
                                    <div class="mb-3 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-center">
                                        <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada foto</p>
                                    </div>
                                @endif

                                <input id="pas_foto" name="pas_foto" type="file"
                                       accept=".jpg,.jpeg,.png"
                                       @if(!$profile->pas_foto_path) required @endif
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('pas_foto')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                            </div>

                            <!-- SCAN KTP -->
                            <div>
                                <label for="scan_ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Scan KTP / Identitas <span class="text-red-500">*</span>
                                </label>

                                @if ($profile->scan_ktp_path)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $profile->scan_ktp_path) }}"
                                             alt="Scan KTP"
                                             class="w-64 rounded-md border-2 border-gray-200 dark:border-gray-600">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dokumen saat ini</p>
                                    </div>
                                @else
                                    <div class="mb-3 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-center">
                                        <i class="fas fa-file text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada dokumen</p>
                                    </div>
                                @endif

                                <input id="scan_ktp" name="scan_ktp" type="file"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       @if(!$profile->scan_ktp_path) required @endif
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                @error('scan_ktp')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, JPEG, PNG, PDF (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-red-500">*</span> Menandakan field wajib diisi
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            {{ __('Simpan Data') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Character counters
        const deskripsiTextarea = document.getElementById('deskripsi_singkat');
        const alamatTextarea = document.getElementById('alamat_lengkap');
        const deskripsiCount = document.getElementById('deskripsi_count');
        const alamatCount = document.getElementById('alamat_count');

        if (deskripsiTextarea && deskripsiCount) {
            deskripsiTextarea.addEventListener('input', function() {
                deskripsiCount.textContent = this.value.length;
            });
            deskripsiCount.textContent = deskripsiTextarea.value.length;
        }

        if (alamatTextarea && alamatCount) {
            alamatTextarea.addEventListener('input', function() {
                alamatCount.textContent = this.value.length;
            });
            alamatCount.textContent = alamatTextarea.value.length;
        }

        // Simple progress indicator
        function updateProgress() {
            const fields = [
                'name', 'nama_panggilan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                'kewarganegaraan', 'agama', 'golongan_darah', 'deskripsi_singkat',
                'jenis_identitas', 'nomor_identitas', 'nomor_hp', 'email_cadangan',
                'kontak_darurat_nama', 'kontak_darurat_hubungan', 'kontak_darurat_nomor',
                'provinsi_id', 'kabupaten_kota', 'kecamatan', 'kelurahan_desa', 'rt_rw',
                'kode_pos', 'alamat_lengkap', 'status_peserta', 'pendidikan_terakhir',
                'nama_sekolah_kampus', 'jurusan', 'nisn_nim', 'pekerjaan', 'instansi_perusahaan', 'jabatan'
            ];

            let filled = 0;
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field && field.value.trim() !== '') {
                    filled++;
                }
            });

            const progressBar = document.querySelector('.bg-primary-600');
            const progressText = document.querySelector('.text-xs.text-gray-600');

            if (progressBar && progressText) {
                const percentage = (filled / fields.length) * 100;
                progressBar.style.width = percentage + '%';
                progressText.textContent = `Kelengkapan data: ${Math.round(percentage)}% (${filled}/${fields.length} field terisi)`;
            }
        }

        // Update progress on input
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', updateProgress);
            field.addEventListener('change', updateProgress);
        });

        // Initial progress
        updateProgress();
    });
</script>
@endsection
