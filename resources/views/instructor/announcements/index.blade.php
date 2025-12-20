@extends('instructor.layouts.app')

@section('title', 'Kelola Pengumuman Program')

@section('content')
<div class="container mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Pengumuman Program</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Informasi untuk peserta di program kelolaan Anda.</p>
        </div>
        <a href="{{ route('instructor.announcements.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow transition">
            <i class="fas fa-plus mr-2"></i> Buat Pengumuman
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-3">Prioritas</th>
                    <th class="px-6 py-3">Program</th>
                    <th class="px-6 py-3">Judul</th>
                    <th class="px-6 py-3">Status Baca</th>
                    <th class="px-6 py-3">Lampiran</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($announcements as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">
                        @if($item->priority == 'critical')
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">
                                <i class="fas fa-exclamation-circle mr-1"></i> PENTING
                            </span>
                        @elseif($item->priority == 'important')
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-700">
                                <i class="fas fa-info-circle mr-1"></i> Info
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-blue-100 text-blue-700">
                                Normal
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-bold">
                            {{ $item->program->title }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $item->title }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($item->content, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-400">
                        {{-- Menghitung berapa user yang sudah baca dari relasi pivot --}}
                        <i class="fas fa-eye mr-1 text-blue-500"></i> {{ $item->readers->count() }} Dibaca
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($item->attachment_path)
                            <a href="{{ Storage::url($item->attachment_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                                <i class="fas fa-paperclip mr-1"></i> File
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($item->created_by == Auth::id())
                            <form action="{{ route('instructor.announcements.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus pengumuman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs italic">Oleh: {{ $item->creator->name }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-bullhorn text-4xl mb-3 text-gray-300"></i>
                        <p>Belum ada pengumuman untuk program Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection
