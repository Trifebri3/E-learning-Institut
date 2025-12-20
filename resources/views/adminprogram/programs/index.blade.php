@extends('adminprogram.layouts.app')


@section('title', 'Program Kelolaan Saya')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Program Kelolaan Saya</h1>
        <p class="text-gray-600 dark:text-gray-400">Daftar program yang ditugaskan kepada Anda.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        @if($programs->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-folder-open text-4xl mb-3"></i>
                <p>Anda belum ditugaskan ke program manapun.</p>
            </div>
        @else
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3">Program</th>
                        <th class="px-6 py-3">Kode Redeem</th>
                        <th class="px-6 py-3">Peserta</th>
                        <th class="px-6 py-3">Status Waktu</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($programs as $program)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded bg-indigo-100 flex items-center justify-center mr-3 text-indigo-500 font-bold text-sm">
                                    {{ substr($program->title, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $program->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $program->lokasi }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-600 rounded text-xs font-mono font-bold">
                                {{ $program->redeem_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-bold text-indigo-600">{{ $program->participants_count }}</span>
                            <span class="text-gray-400">/ {{ $program->kuota }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $now = \Carbon\Carbon::now();
                                $start = \Carbon\Carbon::parse($program->waktu_mulai);
                                $end = \Carbon\Carbon::parse($program->waktu_selesai);
                            @endphp

                            @if($now->between($start, $end))
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Berlangsung</span>
                            @elseif($now->lessThan($start))
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">Akan Datang</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">Selesai</span>
                            @endif
                        </td>
<td class="px-6 py-4 text-right flex justify-end gap-2">
    <!-- Kelola Konten -->
    <a href="{{ route('adminprogram.programs.edit', $program->id) }}"
       class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg text-sm font-medium transition shadow flex items-center">
        <i class="fas fa-edit mr-1"></i> Kelola Konten
    </a>

    <!-- Cetak Presensi Program -->
    <a href="{{ route('adminprogram.presensi.exportProgram', ['program_id' => $program->id]) }}" target="_blank"
       class="text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded-lg text-sm font-medium transition shadow flex items-center">
        <i class="fas fa-print mr-1"></i> Cetak Presensi
    </a>
</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
