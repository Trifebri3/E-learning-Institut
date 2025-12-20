@extends('superadmin.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kelola Program</h1>
        <a href="{{ route('superadmin.programs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow">
            <i class="fas fa-plus mr-2"></i> Buat Program
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm uppercase font-bold">
                <tr>
                    <th class="px-6 py-3">Nama Program</th>
                    <th class="px-6 py-3">Kode Redeem</th>
                    <th class="px-6 py-3">Peserta/Kuota</th>
                    <th class="px-6 py-3">Admin Pengelola</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($programs as $program)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($program->logo_path)
                                <img class="h-10 w-10 rounded object-cover mr-3" src="{{ Storage::url($program->logo_path) }}" alt="">
                            @else
                                <div class="h-10 w-10 rounded bg-indigo-100 flex items-center justify-center mr-3 text-indigo-500 font-bold">
                                    {{ substr($program->title, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white">{{ $program->title }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($program->waktu_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($program->waktu_selesai)->format('d M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs font-mono font-bold text-gray-700 dark:text-gray-300">
                            {{ $program->redeem_code }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="{{ $program->participants_count >= $program->kuota ? 'text-red-600 font-bold' : 'text-green-600' }}">
                            {{ $program->participants_count }}
                        </span>
                        / {{ $program->kuota }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach($program->admins as $admin)
                                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-800"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=random"
                                     title="{{ $admin->name }}">
                            @endforeach
                            @if($program->admins->isEmpty())
                                <span class="text-xs text-gray-400 italic">Belum ada admin</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                            <a href="{{ route('superadmin.programs.show', $program->id) }}" class="text-green-600 hover:text-green-900 mr-3 font-medium">
        <i class="fas fa-eye"></i> Monitor
    </a>
                        <a href="{{ route('superadmin.programs.edit', $program->id) }}" class="text-blue-600 hover:text-blue-900 mr-3 font-medium">Edit</a>
                        <form action="{{ route('superadmin.programs.destroy', $program->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus program ini? Semua data terkait (kelas, nilai, dll) akan hilang!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $programs->links() }}
        </div>
    </div>
</div>
@endsection
