@extends('superadmin.layouts.app')


@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit User: {{ $user->name }}</h1>
        <a href="{{ route('superadmin.users.index') }}" class="text-gray-500 hover:text-blue-600">Kembali</a>
    </div>

    <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- BAGIAN AKUN (KIRI) -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 h-fit">
                <h3 class="text-lg font-bold mb-4 text-blue-600">Informasi Akun</h3>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Role</label>
                    <select name="role" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                        <option value="participant" @selected($user->role == 'participant')>Participant</option>
                        <option value="instructor" @selected($user->role == 'instructor')>Instructor</option>
                        <option value="adminprogram" @selected($user->role == 'adminprogram')>Admin Program</option>
                        <option value="superadmin" @selected($user->role == 'superadmin')>Super Admin</option>
                    </select>
                </div>

                <div class="mt-6 pt-6 border-t dark:border-gray-700">
                    <p class="text-xs text-red-500 mb-2">* Kosongkan jika tidak ingin mengganti password</p>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Password Baru</label>
                        <input type="password" name="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- BAGIAN PROFIL (KANAN) -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-green-600">Data Profil (Biodata)</h3>
                <p class="text-xs text-gray-500 mb-4">Admin dapat membantu mengoreksi data profil user di sini.</p>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Nomor HP</label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $user->profile->nomor_hp) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                        <option value="" @selected(!$user->profile->jenis_kelamin)>- Pilih -</option>
                        <option value="Laki-laki" @selected($user->profile->jenis_kelamin == 'Laki-laki')>Laki-laki</option>
                        <option value="Perempuan" @selected($user->profile->jenis_kelamin == 'Perempuan')>Perempuan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Provinsi</label>
                    <select name="provinsi_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                        <option value="">- Pilih Provinsi -</option>
                        @foreach($provinsiList as $prov)
                            <option value="{{ $prov->id }}" @selected($user->profile->provinsi_id == $prov->id)>{{ $prov->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" rows="3" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ old('alamat_lengkap', $user->profile->alamat_lengkap) }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
