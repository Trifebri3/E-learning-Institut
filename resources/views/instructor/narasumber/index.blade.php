@extends('instructor.layouts.app')

@section('title', 'Narasumber Program ' . $program->title)

@section('content')
<div class="container mx-auto p-6 max-w-6xl">
    <form method="GET" class="mb-4 flex gap-2 items-center">
    <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..." class="px-3 py-2 border rounded w-1/3">
    <select name="kelas_id" class="px-3 py-2 border rounded">
        <option value="">-- Semua Kelas --</option>
        @foreach($kelas as $k)
            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->title }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Filter</button>
</form>

    <div class="flex justify-between items-center mb-6">


        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Narasumber Program: {{ $program->title }}</h1>
        <a href="{{ route('instructor.narasumber.create', $program->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Tambah Narasumber
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($narasumbers->isEmpty())
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-user-tie text-4xl mb-3"></i>
            <p>Belum ada narasumber untuk program ini.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($narasumbers as $n)
                    <tr>
                        <td class="px-6 py-4 flex items-center gap-3">
                            @if($n->foto_path)
                                <img src="{{ asset('storage/'.$n->foto_path) }}" alt="{{ $n->nama }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                                    {{ substr($n->nama, 0, 2) }}
                                </div>
                            @endif
                            <span>{{ $n->nama }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $n->jabatan ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $n->kontak ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @foreach($n->kelas as $k)
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs">{{ $k->title }}</span>
                            @endforeach
                        </td>
<td class="px-6 py-4">
    <div class="flex items-center justify-center gap-2">
        <!-- Lihat Button -->
        <a href="{{ route('instructor.narasumber.show', [$program->id, $n->id]) }}"
           class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
            <i class="fas fa-eye mr-1 text-xs"></i> Lihat
        </a>

        <!-- Edit Button -->
        <a href="{{ route('instructor.narasumber.edit', [$program->id, $n->id]) }}"
           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
            <i class="fas fa-edit mr-1 text-xs"></i> Edit
        </a>

        <!-- Delete Button -->
        <form action="{{ route('instructor.narasumber.destroy', [$program->id, $n->id]) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus narasumber {{ $n->nama }}? Tindakan ini tidak dapat dibatalkan.')"
                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-trash mr-1 text-xs"></i> Hapus
            </button>
        </form>
    </div>
</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
