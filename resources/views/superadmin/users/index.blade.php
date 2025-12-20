@extends('superadmin.layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container mx-auto p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Pengguna</h1>
        <a href="{{ route('superadmin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah User
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6 flex flex-wrap gap-4">
        <a href="{{ route('superadmin.users.index') }}" class="px-3 py-1 rounded-full text-sm {{ !request('role') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">Semua</a>
        <a href="{{ route('superadmin.users.index', ['role' => 'participant']) }}" class="px-3 py-1 rounded-full text-sm {{ request('role') == 'participant' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">Participant</a>
        <a href="{{ route('superadmin.users.index', ['role' => 'instructor']) }}" class="px-3 py-1 rounded-full text-sm {{ request('role') == 'instructor' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600' }}">Instruktur</a>
        <a href="{{ route('superadmin.users.index', ['role' => 'admin_program']) }}" class="px-3 py-1 rounded-full text-sm {{ request('role') == 'admin_program' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600' }}">Admin Program</a>

        <form action="{{ route('superadmin.users.index') }}" method="GET" class="ml-auto flex gap-2">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}" class="px-3 py-1 border rounded text-sm">
            <button type="submit" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm uppercase">
                <tr>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status Profil</th>
                    <th class="px-6 py-3">Terdaftar</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $user->role == 'superadmin' ? 'bg-red-100 text-red-800' :
                              ($user->role == 'admin_program' ? 'bg-yellow-100 text-yellow-800' :
                              ($user->role == 'instructor' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800')) }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->profile && $user->profile->is_complete)
                            <span class="text-green-600 text-xs font-bold"><i class="fas fa-check-circle"></i> Lengkap</span>
                        @else
                            <span class="text-red-500 text-xs"><i class="fas fa-exclamation-circle"></i> Belum Lengkap</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                        <a href="{{ route('superadmin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                        <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>

                        @if($user->role === 'participant')
                        <a href="{{ route('superadmin.users.impersonate', $user->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Login sebagai peserta</a>
                        @endif

 @if($user->role !== 'superadmin' && $user->id !== auth()->id())
    <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="inline-block"
          onsubmit="return confirm('Yakin ingin menghapus user ini? Data terkait (presensi, nilai) akan ikut terhapus.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
    </form>
    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $users->withQueryString()->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
