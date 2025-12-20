@extends('superadmin.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Buat Program Baru</h1>
        <a href="{{ route('superadmin.programs.index') }}" class="text-gray-500 hover:text-blue-600">Kembali</a>
    </div>

    <form action="{{ route('superadmin.programs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- KOLOM KIRI (Detail Utama) -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 text-indigo-600">Informasi Dasar</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Nama Program</label>
                        <input type="text" name="title" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Kode Redeem (Unik)</label>
                            <input type="text" name="redeem_code" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="CTH: IOT-BATCH-1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Kuota Peserta</label>
                            <input type="number" name="kuota" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" value="50" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Lokasi Pelaksanaan</label>
                        <input type="text" name="lokasi" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Online / Zoom / Gedung A" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                            <input type="date" name="waktu_mulai" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                            <input type="date" name="waktu_selesai" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 text-indigo-600">Deskripsi</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Deskripsi Singkat</label>
                        <textarea name="deskripsi_singkat" rows="2" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Deskripsi Lengkap</label>
                        <textarea name="deskripsi_lengkap" rows="5" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN (Media & Admin) -->
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 text-indigo-600">Admin Pengelola</h3>
                    <div class="h-48 overflow-y-auto border rounded p-2 dark:border-gray-700">

@php
$admins = \App\Models\User::where('role', 'adminprogram')->get();
$selectedAdmins = []; // karena belum ada program
@endphp

<div class="h-48 overflow-y-auto border rounded p-2 dark:border-gray-700">
    @foreach($admins as $admin)
        <label class="flex items-center space-x-2 p-1 hover:bg-gray-50 dark:hover:bg-gray-700 rounded cursor-pointer">
            <input type="checkbox" name="admin_ids[]" value="{{ $admin->id }}"
                   @checked(in_array($admin->id, $selectedAdmins))>
            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $admin->name }}</span>
        </label>
    @endforeach
</div>
                </div>
                <div class="mb-4">
    <label class="block font-semibold text-gray-700 mb-2">Pilih Instruktur</label>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($instructors as $ins)
            <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-gray-100 transition">
                <input
                    type="checkbox"
                    name="instructors[]"
                    value="{{ $ins->id }}"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <span class="text-gray-800">{{ $ins->name }}</span>
            </label>
        @endforeach
    </div>
</div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 text-indigo-600">Gambar</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Logo (Kecil)</label>
                        <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Banner (Besar)</label>
                        <input type="file" name="banner" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>



                <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-lg">
                    <i class="fas fa-save mr-2"></i> Simpan Program
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
