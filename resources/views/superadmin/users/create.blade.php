@extends('superadmin.layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Tambah Pengguna Baru</h1>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form action="{{ route('superadmin.users.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input type="text" name="name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Role</label>
                <select name="role" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                    <option value="participant">Participant (Peserta)</option>
                    <option value="instructor">Instructor (Pengajar)</option>
    <option value="adminprogram">Admin Program</option>
                    <option value="superadmin">Super Admin</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" name="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                </div>
            </div>

            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan User</button>
        </form>
    </div>
</div>
@endsection
